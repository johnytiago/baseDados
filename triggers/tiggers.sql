DROP FUNCTION IF EXISTS check_seq_func;
DELIMITER $$
CREATE FUNCTION check_seq_func(seq_id INT) RETURNS INT
BEGIN
  RETURN EXISTS ( SELECT *
     FROM (select idseq from tipo_registo
       UNION
       select idseq from pagina
       UNION
       select idseq from campo
       UNION
       select idseq from registo
       UNION
       select idseq from valor) A
     WHERE A.idseq=seq_id);
END$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_seq_tipo_registo;
DELIMITER $$
CREATE TRIGGER check_seq_tipo_registo
    BEFORE INSERT ON tipo_registo FOR EACH ROW
    BEGIN
        IF (SELECT check_seq_func(NEW.idseq) )!=0
  THEN
   set NEW.userid=null;
  END IF;
    END$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_seq_pagina;
DELIMITER $$
CREATE TRIGGER check_seq_pagina
    BEFORE INSERT ON pagina FOR EACH ROW
    BEGIN
        IF (SELECT check_seq_func(NEW.idseq) )!=0
  THEN
   set NEW.userid=null;
  END IF;
    END$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_seq_campo;
DELIMITER $$
CREATE TRIGGER check_seq_campo
    BEFORE INSERT ON campo FOR EACH ROW
    BEGIN
        IF (SELECT check_seq_func(NEW.idseq) )!=0
  THEN
   set NEW.userid=null;
  END IF;
    END$$
DELIMITER ;

DROP TRIGGER IF EXISTS check_seq_registo;
DELIMITER $$
CREATE TRIGGER check_seq_registo
    BEFORE INSERT ON registo FOR EACH ROW
    BEGIN
        IF (SELECT check_seq_func(NEW.idseq) )!=0
  THEN
   set NEW.userid=null;
  END IF;
    END$$
DELIMITER ;
