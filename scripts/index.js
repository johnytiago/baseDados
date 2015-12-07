var fs = require('fs');
var casual = require('casual');
var async = require('async');

// 1- criar USERSNUM  utilizadores
// 2- criar PAGENUM paginas
// 3- adicionar LOGINNUM logins
// 4- criar TIPONUM tipos
// 5- criar CAMPONUM campos
// 5- criar REGNUM registo
// 5.1- adicionar na reg_pag

// amounts
var USERSNUM = 5000;
var PAGENUM = 5000;
var LOGINNUM = 5000;
var TIPONUM = 5000;
var CAMPONUM = 5000;
var REGNUM = 5000;

// Global variables
var SEQUENCIA = 0;
var REGPAGID = 0;
var FILENAMES = [];

// Output order
FILENAMES.push('./cleanDB.sql');
FILENAMES.push('./populateUtilizador.sql');
FILENAMES.push('./populateLogin.sql');
FILENAMES.push('./populateSequencia.sql');
FILENAMES.push('./populateTipo.sql');
FILENAMES.push('./populatePagina.sql');
FILENAMES.push('./populateCampo.sql');
FILENAMES.push('./populateRegisto.sql');
FILENAMES.push('./populateReg_pag.sql');

// Helper functions
function generateFile(genFunction, fileName, numLines, template) {
  var wStream = fs.createWriteStream(`./${fileName}.sql`);
  wStream.write(template);
  for (var i = 1 ; i < numLines ; i++){
    wStream.write(genFunction(i)+',');
  }
  wStream.end(genFunction(i)+';');
}

function casualItem(){
  var formats = ['{{name}}', '{{month_name}}', '{{city}}', '{{company_name}}'];
  return casual.populate_one_of(formats);
}

function newDate(){
  var date = new Date().toISOString().
    replace(/T/, ' ').      // replace T with a space
    replace(/\..+/, '');     // delete the dot and everything after
  return date;
}

// DROPS all tables
var cleanDB = fs.createWriteStream('./cleanDB.sql');
cleanDB.end(`DELIMITER ;
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

//// sequencia
var wSeq = fs.createWriteStream('./populateSequencia.sql'); //initiates sequence file
wSeq.write("INSERT INTO sequencia (userid, contador_sequencia,moment) VALUES");

function addSequencia(userId){
  var a;
  SEQUENCIA >= 1 ? a = ',': a = ''; // not add comma on first insert
  wSeq.write(`${a}\n(${userId}, ${++SEQUENCIA}, "${newDate()}")`);
  return SEQUENCIA;
}

// UTILIZADOR
function generateUser(id) {
  return `\n(${id}, "${casual.email}", "${casual.name}", "${casual.password}", "${casual.words(n=3)+'?'}", "${casual.word}", "${casual.words(n=3)+'?'}", "${casual.word}", "${casual.country}", "${casual.letter}")`;
}

var viewUser = "INSERT INTO utilizador (userid, email, nome, password, questao1, resposta1, questao2, resposta2, pais, categoria) VALUES ";
generateFile(generateUser, "populateUtilizador", USERSNUM, viewUser);

// PAGINA
function generatePagina() {
  var userId = casual.integer(from = 1, to = USERSNUM);
  return `\n(${userId}, ${addSequencia(userId)}, "${casualItem()}", ${+casual.coin_flip})`;
}

var viewPagina = "INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa) VALUES";
generateFile(generatePagina, "populatePagina", PAGENUM, viewPagina);

// LOGIN
function generateLogin(id) {
  return `\n(${casual.integer(from = 1, to = USERSNUM)}, ${id}, ${casual.integer(from = 0, to = 1)}, "${newDate()}")`;
}

var viewLogin = "INSERT INTO login (userid, contador_login, sucesso, moment) VALUES";
generateFile(generateLogin, "populateLogin", LOGINNUM, viewLogin);

// TIPO
function generateTipo(id) {
  var userId = casual.integer(from = 1, to = USERSNUM);
  return `\n(${userId}, ${id}, "${casualItem()}", ${addSequencia(userId)}, ${+casual.coin_flip})`;
}

var viewTipo = "INSERT INTO tipo_registo (userid, typecnt, nome, idseq, ativo) VALUES";
generateFile(generateTipo, "populateTipo", TIPONUM, viewTipo);

// CAMPO
function generateCampo(id) {
  var userId = casual.integer(from = 1, to = USERSNUM);
  return `\n(${userId}, ${casual.integer(from = 1, to = TIPONUM)}, ${id}, "${casualItem()}", ${addSequencia(userId)}, ${+casual.coin_flip})`;
}

var viewCampo = "INSERT INTO campo (userid, typecnt, campocnt, nome, idseq, ativo) VALUES";
generateFile(generateCampo, "populateCampo", CAMPONUM, viewCampo);

// REGISTO + REG_PAG
function generateRegisto(id) {
  var userId = casual.integer(from = 1, to = USERSNUM);
  var tipoId = casual.integer(from = 1, to = TIPONUM);
  generateReg_pag(++REGPAGID, userId, tipoId, id);   // called from here to make sure the par (userid, tipoid, regid) is right
  return `\n(${userId}, ${tipoId}, ${id}, "${casualItem()}", ${addSequencia(userId)}, ${+casual.coin_flip})`;
}

var viewRegisto = "INSERT INTO registo (userid, typecounter, regcounter, nome, idseq, ativo) VALUES";
var viewReg_pag = "INSERT INTO reg_pag (idregpag, userid, typeid, pageid, regid, idseq, ativa) VALUES";
var reg_pagStream = fs.createWriteStream('./populateReg_pag.sql');
reg_pagStream.write(viewReg_pag);           // Iniciates the reg_pag file
reg_pagStream.destroy();                    
var aStream = fs.createWriteStream('./populateReg_pag.sql', {flags: 'a'});  // from now on only appends to the initiated file
generateFile(generateRegisto, "populateRegisto", REGNUM, viewRegisto);

function generateReg_pag(id, userId, tipoId, regId) {
  var a;
  REGPAGID === 1 ? a = '': a = ','; // not add comma on first insert
  aStream.write(`${a}\n(${id}, ${userId}, ${tipoId}, ${casual.integer(from = 1, to = PAGENUM)}, ${regId}, ${addSequencia(userId)}, ${+casual.coin_flip})`);
}

// Last thing to do after all files are created: close sequence count
aStream.end(';');
wSeq.end(';');

//Aggregate all the results in one file
fs.createWriteStream('./populateDB.sql').write('');
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
