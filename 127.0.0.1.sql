-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 17 2019 г., 02:46
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `db_users`
--
CREATE DATABASE `db_users` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_users`;

-- --------------------------------------------------------

--
-- Структура таблицы `user_autentificate`
--

CREATE TABLE IF NOT EXISTS `user_autentificate` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `user_autentificate`
--

INSERT INTO `user_autentificate` (`userid`, `username`, `password`) VALUES
(1, 'admin', '698d51a19d8a121ce581499d7b701668');
--
-- База данных: `db_zv`
--
CREATE DATABASE `db_zv` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_zv`;

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`root`@`localhost` FUNCTION `addEmployee`(input_surname VARCHAR( 255 ), input_name VARCHAR( 255 ), input_patronym VARCHAR( 255 ), input_post VARCHAR( 255 )) RETURNS int(11)
Begin

	SELECT 
		count(*)
	FROM	
		employee
	WHERE 
		surname = input_surname and
                name = input_name and
                patronym = input_patronym and
                post = input_post
                
	INTO
		@founded;


	if @founded != 0 then
set @result = 0;
	else
		INSERT INTO employee
			VALUES(NULL,input_surname ,input_name, input_patronym, input_post);
		set @result = 1;

	end IF;

	return(@result);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `addHardware`(input_model VARCHAR( 255 ), input_variant VARCHAR( 255 )) RETURNS int(11)
Begin

	SELECT 
		count(*)
	FROM	
		hardware
	WHERE 
		model = input_model and
                variant = input_variant
	INTO
		@founded;


	if @founded != 0 then
set @result = 0;
	else
		INSERT INTO hardware
			VALUES(NULL,input_model, input_variant);
		set @result = 1;

	end IF;

	return(@result);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `addImplementation`(input_model VARCHAR( 255 ), input_name VARCHAR( 255 ), input_unit VARCHAR( 255 ), input_price FLOAT) RETURNS int(11)
Begin
	SELECT 
		ID_hardware
	FROM	
		hardware
	WHERE 
		model = input_model
	INTO
		@input_ID_hardware;
	
        
        SELECT 
		ID_shop
	FROM	
		shop
	WHERE 
		name = input_name
	INTO
		@input_ID_shop;
        
        SELECT 
		count(*)
	FROM	
		implementation
	WHERE 
		model = input_model and
                name = input_name and
                unit = input_unit and
                price = input_price
	INTO
		@founded;


	if @founded != 0 then
set @result = 0;
	else
		INSERT INTO implementation
			VALUES(NULL,input_model ,input_name, input_unit, input_price,@input_ID_hardware, @input_ID_shop);
		set @result = 1;

	end IF;

	return(@result);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `addRequest`(input_model VARCHAR( 255 ), input_surname VARCHAR( 255 ),  input_number int) RETURNS int(11)
Begin
	SELECT 
		ID_hardware
	FROM	
		hardware
	WHERE 
		model = input_model
	INTO
		@input_ID_hardware;
        
        SELECT 
		ID_employee
	FROM	
		employee
	WHERE 
		surname = input_surname
	INTO
		@input_ID_employee;
		INSERT INTO request (`ID_request`, `model`, `name`, `surname`, `number`, `date_start`, `date_finish`, `state`, `ID_hardware` , `ID_shop`, `ID_employee` ) 
			VALUES(NULL, input_model, '',  input_surname, input_number,'', '', '', @input_ID_hardware, 1, @input_ID_employee);
		set @result = 1;

	return(@result);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `addShop`(input_name VARCHAR( 255 ), input_adress VARCHAR( 255 )) RETURNS int(11)
Begin

	SELECT 
		count(*)
	FROM	
		shop
	WHERE 
		name = input_name and
                adress = input_adress
	INTO
		@founded;


	if @founded != 0 then
set @result = 0;
	else
		INSERT INTO shop
			VALUES(NULL,input_name ,input_adress);
		set @result = 1;

	end IF;

	return(@result);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `delEmployee`(input_ID_employee INT) RETURNS int(11)
Begin

SELECT 
		count(*)
	FROM	
		request
	WHERE 
		ID_employee = input_ID_employee
	INTO
		@founded;



	if (@founded != 0) then
		set @result = -1;
	else
		DELETE FROM
		employee
		WHERE 
			ID_employee = input_ID_employee;
		set @result = 0;

	end if;

	return(@result);
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `delHardware`(input_ID_hardware INT) RETURNS int(11)
Begin

	SELECT 
		count(*)
	FROM	
		implementation
	WHERE 
		ID_hardware = input_ID_hardware
	INTO
		@founded;
	
        SELECT 
		count(*)
	FROM	
		request
	WHERE 
		ID_hardware = input_ID_hardware
	INTO
		@found;
             	
                if (@founded != 0 and @found != 0) then
		set @result = -3;
              	
                elseif (@founded = 0 and @found != 0) then
		set @result = -2;
   		
                elseif (@founded != 0 and @found = 0) then
		set @result = -1;
        
	else
		DELETE FROM
		hardware
		WHERE 
			ID_hardware = input_ID_hardware;
		set @result = 0;

	end if;

	return(@result);
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `delImplementation`(input_ID_implementation INT) RETURNS int(11)
Begin
		DELETE FROM
		implementation
		WHERE 
			ID_implementation = input_ID_implementation;
		set @result = 0;

	return(@result);
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `delRequest`(input_ID_request INT) RETURNS int(11)
Begin
		DELETE FROM
		request
		WHERE 
			ID_request = input_ID_request;
		set @result = 0;

	return(@result);
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `delShop`(input_ID_shop INT) RETURNS int(11)
Begin

	SELECT 
		count(*)
	FROM	
		implementation
	WHERE 
		ID_shop = input_ID_shop
	INTO
		@founded;
SELECT 
		count(*)
	FROM	
		request
	WHERE 
		ID_shop = input_ID_shop
	INTO
		@found;



	if (@founded != 0 and @found != 0) then
		set @result = -3;
                
                elseif (@founded = 0 and @found != 0) then
		set @result = -2;
                
                elseif (@founded != 0 and @found = 0) then
		set @result = -1;
	else
		DELETE FROM
		shop
		WHERE 
			ID_shop = input_ID_shop;
		set @result = 0;

	end if;

	return(@result);
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `updEmployee`( input_surname VARCHAR( 255 ), input_name VARCHAR( 255 ), input_patronym VARCHAR( 255 ), input_post VARCHAR( 255 ), input_ID_employee INT) RETURNS int(11)
Begin

	        SELECT 
		count(*)
	FROM	
		employee
	WHERE 
		surname = input_surname and
                name = input_name and
                patronym = input_patronym and
                post = input_post
	INTO
		@founded;


	if (@founded != 0) then
		set @result = 0;
	else
		 UPDATE employee
                 SET surname = input_surname, name = input_name,patronym = input_patronym, post = input_post 
		WHERE 
			ID_employee = input_ID_employee;
                    set @result = 1;

	end if;

	return(@result);
        end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `updHardware`( input_model VARCHAR( 255 ), input_variant VARCHAR( 255 ), input_ID_hardware INT) RETURNS int(11)
Begin

	        SELECT 
		count(*)
	FROM	
		hardware
	WHERE 
		model = input_model and
                variant = input_variant
	INTO
		@founded;


	if (@founded != 0) then
		set @result = 0;
	else
		 UPDATE hardware
                 SET model = input_model, variant = input_variant
		WHERE 
			ID_hardware = input_ID_hardware;
                    set @result = 1;

	end if;

	return(@result);
        end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `updImplementation`(input_model VARCHAR( 255 ), input_name VARCHAR( 255 ), input_unit VARCHAR( 255 ), input_price FLOAT, input_ID_implementation INT) RETURNS int(11)
Begin
	SELECT 
		ID_hardware
	FROM	
		hardware
	WHERE 
		model = input_model
	INTO
		@input_ID_hardware;
	
        
        SELECT 
		ID_shop
	FROM	
		shop
	WHERE 
		name = input_name
	INTO
		@input_ID_shop;
        
       	SELECT 
		count(*)
	FROM	
		implementation
	WHERE 
		ID_implementation = input_ID_implementation
	INTO
		@founded;


	if (@founded = 0) then
		set @result = 0;
	else
		 UPDATE implementation
                 SET model = input_model, name = input_name, unit = input_unit, price = input_price, ID_hardware = @input_ID_hardware,ID_shop = @input_ID_shop
		WHERE 
			ID_implementation = input_ID_implementation;
                    set @result = 1;

	end if;

	return(@result);
        end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `updRequest`(input_name VARCHAR( 255 ), input_date_start VARCHAR( 255 ), input_date_finish VARCHAR( 255 ), input_state VARCHAR( 255 ), input_ID_request INT) RETURNS int(11)
Begin
	SELECT 
		ID_shop
	FROM	
		shop
	WHERE 
		name = input_name
	INTO
		@input_ID_shop;
		
        UPDATE request
        SET name = input_name, date_start = input_date_start, date_finish = input_date_finish, state = input_state,ID_shop = @input_ID_shop
		WHERE 
			ID_request = input_ID_request;

	return(@result);

end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `updShop`( input_name VARCHAR( 255 ), input_adress VARCHAR( 255 ), input_ID_shop INT) RETURNS int(11)
Begin

	        SELECT 
		count(*)
	FROM	
		shop
	WHERE 
		name = input_name and
                adress = input_adress
	INTO
		@founded;


	if (@founded != 0) then
		set @result = 0;
	else
		 UPDATE shop
                 SET name = input_name, adress = input_adress
		WHERE 
			ID_shop = input_ID_shop;
                    set @result = 1;

	end if;

	return(@result);
        end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `ID_employee` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `patronym` varchar(255) NOT NULL,
  `post` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_employee`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `employee`
--

INSERT INTO `employee` (`ID_employee`, `surname`, `name`, `patronym`, `post`) VALUES
(1, 'Суслов', 'Алексей', 'Александрович', 'Инженер - электроник');

-- --------------------------------------------------------

--
-- Структура таблицы `hardware`
--

CREATE TABLE IF NOT EXISTS `hardware` (
  `ID_hardware` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `variant` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_hardware`),
  UNIQUE KEY `ID_hardware` (`ID_hardware`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `hardware`
--

INSERT INTO `hardware` (`ID_hardware`, `model`, `variant`) VALUES
(12, 'HP CE 310A', 'Картридж'),
(14, 'RJ-11 (4p4c)', 'Коннектор'),
(16, 'DDRAM 1Gb 400', 'Модуль памяти'),
(17, 'C-EXV', 'Тонер');

-- --------------------------------------------------------

--
-- Структура таблицы `implementation`
--

CREATE TABLE IF NOT EXISTS `implementation` (
  `ID_implementation` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `ID_hardware` int(11) NOT NULL,
  `ID_shop` int(11) NOT NULL,
  PRIMARY KEY (`ID_implementation`),
  KEY `ID_hardware` (`ID_hardware`),
  KEY `ID_shop` (`ID_shop`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `implementation`
--

INSERT INTO `implementation` (`ID_implementation`, `model`, `name`, `unit`, `price`, `ID_hardware`, `ID_shop`) VALUES
(9, 'DDRAM 1Gb 400', 'Анкор', 'шт.', 365, 16, 1),
(10, 'C-EXV', 'Битрейд', 'шт.', 542, 17, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `ID_request` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `number` float DEFAULT NULL,
  `date_start` varchar(255) DEFAULT NULL,
  `date_finish` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `ID_hardware` int(11) DEFAULT NULL,
  `ID_shop` int(11) DEFAULT NULL,
  `ID_employee` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_request`),
  KEY `ID_hardware` (`ID_hardware`,`ID_shop`,`ID_employee`),
  KEY `ID_shop` (`ID_shop`),
  KEY `ID_employee` (`ID_employee`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Дамп данных таблицы `request`
--

INSERT INTO `request` (`ID_request`, `model`, `name`, `surname`, `number`, `date_start`, `date_finish`, `state`, `ID_hardware`, `ID_shop`, `ID_employee`) VALUES
(9, 'C-EXV6', 'Анкор', 'Суслов', 5, '04-05-2015', '08-05-2015', 'обработка', 17, 1, 1),
(11, 'DDRAM 1Gb 400', 'Анкор', 'Суслов', 2, '04-05-2015', '08-05-2015', 'обработка', 16, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `ID_shop` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `adress` varchar(255) NOT NULL,
  PRIMARY KEY (`ID_shop`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `shop`
--

INSERT INTO `shop` (`ID_shop`, `name`, `adress`) VALUES
(1, 'Анкор', 'Центральный, 40'),
(3, 'Битрейд', 'Юбилейный, 25');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `implementation`
--
ALTER TABLE `implementation`
  ADD CONSTRAINT `implementation_ibfk_1` FOREIGN KEY (`ID_hardware`) REFERENCES `hardware` (`ID_hardware`),
  ADD CONSTRAINT `implementation_ibfk_2` FOREIGN KEY (`ID_shop`) REFERENCES `shop` (`ID_shop`);

--
-- Ограничения внешнего ключа таблицы `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`ID_hardware`) REFERENCES `hardware` (`ID_hardware`),
  ADD CONSTRAINT `request_ibfk_2` FOREIGN KEY (`ID_shop`) REFERENCES `shop` (`ID_shop`),
  ADD CONSTRAINT `request_ibfk_3` FOREIGN KEY (`ID_employee`) REFERENCES `employee` (`ID_employee`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
