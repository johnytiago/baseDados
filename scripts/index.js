var fs = require('fs');
var util = require('util');
var casual = require('casual');
var async = require('async');

// amounts
var USERSNUM = 50000;
var PAGENUM = 10;
var TIPONUM = 10;
var CAMPNUM = 10;
var REGNUM = 10;
//var LOGINNUM = USERSNUM;

// Global variables
var SEQUENCIA = 0;
var REGPAGID = 0;
var REGID = 0;
var FILENAMES = [];
var STREAMS= {};

// Output order
FILENAMES.push('./tables/cleanDB.sql');
FILENAMES.push('./tables/populateUtilizador.sql');
FILENAMES.push('./tables/populateSequencia.sql');
FILENAMES.push('./tables/populatePagina.sql');
FILENAMES.push('./tables/populateTipo.sql');
FILENAMES.push('./tables/populateRegisto.sql');
//FILENAMES.push('./tables/populateCampo.sql');
FILENAMES.push('./tables/populateReg_pag.sql');
//FILENAMES.push('./tables/populateLogin.sql');

// Creates destination folder
try {
  fs.mkdirSync('./tables');
} catch (err) {
  // folder exists nothing to do
}

// Fill files headers
function initiateFile(fileName, header) {
  var wStream = fs.createWriteStream(`./tables/${fileName}.sql`);
  STREAMS[fileName] = wStream;
  wStream.write(header);
}

// Initiates all files to be written
initiateFile("cleanDB", `DELIMITER ;
SET foreign_key_checks = 0 ;

TRUNCATE TABLE login;
TRUNCATE TABLE campo;
TRUNCATE TABLE pagina;
TRUNCATE TABLE reg_pag;
TRUNCATE TABLE registo;
TRUNCATE TABLE sequencia;
TRUNCATE TABLE tipo_registo;
TRUNCATE TABLE utilizador;

SET foreign_key_checks = 1 ;`);
initiateFile("populateUtilizador", "INSERT INTO utilizador (userid, email, nome, password, questao1, resposta1, questao2, resposta2, pais, categoria) VALUES");
initiateFile('populateSequencia', 'INSERT INTO sequencia (userid, contador_sequencia, moment) VALUES ' ); //initiates sequence file
initiateFile('populatePagina', "INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa) VALUES ");
initiateFile('populateTipo',"INSERT INTO tipo_registo (userid, typecnt, nome, idseq, ativo) VALUES ");
//initiateFile('populateCampo', "INSERT INTO campo (userid, typecnt, campocnt, nome, idseq, ativo) VALUES ");
initiateFile('populateRegisto', "INSERT INTO registo (userid, typecounter, regcounter, nome, idseq, ativo) VALUES ");
initiateFile('populateReg_pag', "INSERT INTO reg_pag (idregpag, userid, typeid, pageid, regid, idseq, ativa) VALUES ");
//initiateFile("populateLogin", "INSERT INTO login (userid, contador_login, sucesso, moment) VALUES");

// ####### Helper functions ######
function casualItem(){
  var formats = ['{{name}}', '{{city}}', '{{company_name}}'];
  return casual.populate_one_of(formats);
}

function newDate(){
  var date = new Date().toISOString().
    replace(/T/, ' ').      // replace T with a space
    replace(/\..+/, '');     // delete the dot and everything after
  return date;
}

// sequencia
function addSequencia(userId){
  var comma;
  SEQUENCIA >= 1 ? comma = ',': comma = ''; // not add comma on first insert
  STREAMS['populateSequencia'].write(`${comma}\n(${userId}, ${++SEQUENCIA}, "${newDate()}")`);
  return SEQUENCIA;
}

// LOGIN
function generateLogin(id) {
  return `\n(${casual.integer(from = 1, to = USERSNUM)}, ${id}, ${casual.coin_flip}, "${newDate()}")`;
}

// UTILIZADOR
function generateUser(options) {
  return `${options.comma}\n(${options.userId}, "${`${options.id}`+casual.email}", "${casual.name}", "${casual.password}", "${casual.words(n=3)+'?'}", "${casual.word}", "${casual.words(n=3)+'?'}", "${casual.word}", "${casual.country}", "${casual.letter}")`;
}

// PAGINA
function generatePagina(options) {
  return `${options.comma}\n(${options.userId}, ${options.pageId}, "${casualItem()}", ${addSequencia(options.userId)}, ${casual.coin_flip})`;
}

// TIPO
function generateTipo(options) {
  return `${options.comma}\n(${options.userId}, ${options.tipoId}, '${casualItem()}', ${addSequencia(options.userId)}, ${casual.coin_flip})`;
}

// CAMPO
function generateCampo(options) {
  return `${options.comma}\n(${options.userId}, ${options.tipoId}, ${options.campoId}, "${casualItem()}", ${addSequencia(options.userId)}, ${casual.coin_flip})`;
}

function generateRegisto(options) {
  return `${options.comma}\n(${options.userId}, ${options.tipoId}, ${options.regId}, "${casualItem()}", ${addSequencia(options.userId)}, ${casual.coin_flip})`;
}

function generateReg_pag(options) {
  return `${options.comma}\n(${options.regPagId}, ${options.userId}, ${options.tipoId}, ${options.pageId}, ${options.regId}, ${addSequencia(options.userId)}, ${casual.coin_flip})`;
}

function writeLine(genFunction, fileName, options) {
  options.id === 1 ? options.comma= '': options.comma = ','; // not add comma on first insert
  STREAMS[fileName].write(genFunction(options));
}

var tipoIdRange = {
  bottom: 1,
  top: TIPONUM
};
var pageIdRange = {
  bottom: 1,
  top: PAGENUM
};

for (var userId=1 ; userId <= USERSNUM; userId++) {
  writeLine(generateUser, 'populateUtilizador', {userId, id: userId});

  // cr8 TIPONUM tipos
  for(var tipoId = tipoIdRange.bottom ; tipoId <= tipoIdRange.top ; tipoId++) {
    writeLine(generateTipo, 'populateTipo', {userId, tipoId, id: tipoId});
  }

  for(var pageId = pageIdRange.bottom ; pageId <= pageIdRange.top ; pageId++) {
    writeLine(generatePagina, 'populatePagina', {userId, pageId, id: pageId});
  }

  // cr8 REGNUM registos and the corresponding reg_pag lines
  for(var regId = 1 ; regId <= REGNUM ; regId++) {
    var tipoId= casual.integer(from= tipoIdRange.bottom, to= tipoIdRange.top);
    var options = {
      userId, 
      regId: ++REGID,
      tipoId,
      pageId: casual.integer(from= pageIdRange.bottom, to= pageIdRange.top),
      regPagId: ++REGPAGID,
      id: REGID
    }
    writeLine(generateRegisto, 'populateRegisto', options); 
    options.id = REGPAGID;
    writeLine(generateReg_pag, 'populateReg_pag', options); 
  }

  // Store the range to pick for the same use
  tipoIdRange.bottom += TIPONUM;
  tipoIdRange.top += TIPONUM;
  pageIdRange.bottom += PAGENUM;
  pageIdRange.top += PAGENUM;

  //for(var campoId = 1 ; campoId <= CAMPNUM ; campoId++) {
    //writeLine(generateCampo, 'populateCampo', {
      //userId,
      //campoId,
      //tipoId: casual.integer(from= tipoIdRange.bottom, to= tipoIdRange.top),
      //id: campoId
    //}); 
  //}

}

// Last thing to do after all files are written
var filesNames = FILENAMES.slice(1).map(function (name){
  return name.match(/(\w*)(?=.sql)/g)[0]; // get the name of the file
});

async.each(filesNames, endFile, function(err){
  if(err)
    return console.error('Error finalizing files');

  concatResults();
});

function endFile(filename, callback) {
  STREAMS[filename].write(';');
  callback();
}

// Concatenate all the results in one file
function concatResults(){
  fs.createWriteStream('./populateDB.sql').write(''); //cleans file now to use a flag then
  async.eachSeries(FILENAMES, concatFiles, function(){ 
    console.log("Files concatenated!")
  });

  function concatFiles(fileName, cb) {
    var writeStream = fs.createWriteStream('./populateDB.sql', {flags: 'a'})
    var readStream = fs.createReadStream(fileName);

    readStream.pipe(writeStream, {end: false});
    readStream.on('end', function() {
      writeStream.end('\n\n');
      return cb();
    });
  }
}
