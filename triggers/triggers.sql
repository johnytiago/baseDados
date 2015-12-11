DELIMITER $$
DROP FUNCTION IF EXISTS check_seq_func $$
CREATE FUNCTION check_seq_func(seq_id INT) RETURNS INT
BEGIN
  RETURN EXISTS ( SELECT *
     FROM (SELECT idseq FROM tipo_registo
	   UNION
	   SELECT idseq FROM pagina
	   UNION
	   SELECT idseq FROM campo
	   UNION
	   SELECT idseq FROM registo
	   UNION
	   SELECT idseq FROM valor
   	   UNION
	   SELECT idseq FROM reg_pag) A
     WHERE A.idseq=seq_id);
END$$


DROP TRIGGER IF EXISTS check_seq_tipo_registo $$
CREATE TRIGGER check_seq_tipo_registo
    BEFORE INSERT ON tipo_registo FOR EACH ROW
BEGIN
	IF (SELECT check_seq_func(NEW.idseq))
		THEN set NEW=null;
	END IF;
END$$


DROP TRIGGER IF EXISTS check_seq_pagina $$
CREATE TRIGGER check_seq_pagina
    BEFORE INSERT ON pagina FOR EACH ROW
BEGIN
	IF (SELECT check_seq_func(NEW.idseq))
		THEN set NEW=null;
	END IF;
END$$


DROP TRIGGER IF EXISTS check_seq_campo $$
CREATE TRIGGER check_seq_campo
    BEFORE INSERT ON campo FOR EACH ROW
BEGIN
	IF (SELECT check_seq_func(NEW.idseq))
		THEN set NEW=null;
	END IF;
END$$

DROP TRIGGER IF EXISTS check_seq_registo $$
CREATE TRIGGER check_seq_registo
    BEFORE INSERT ON registo FOR EACH ROW
BEGIN
	IF (SELECT check_seq_func(NEW.idseq))
		THEN set NEW=null;
	END IF;
END$$


DROP TRIGGER IF EXISTS check_seq_valor $$
CREATE TRIGGER check_seq_valor
    BEFORE INSERT ON valor FOR EACH ROW
BEGIN
	IF (SELECT check_seq_func(NEW.idseq))
		THEN set NEW=null;
	END IF;
END$$


DROP TRIGGER IF EXISTS check_seq_reg_pag $$
CREATE TRIGGER check_seq_reg_pag
    BEFORE INSERT ON reg_pag FOR EACH ROW
BEGIN
	IF (SELECT check_seq_func(NEW.idseq))
		THEN set NEW=null;
	END IF;
END$$
DELIMITER ;
