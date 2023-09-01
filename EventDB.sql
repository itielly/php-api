CREATE DATABASE eventDB;

CREATE TABLE Event (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name varchar(100),
    dayEvent DATE,
    initHour TIME,
    finishHour TIME,
    description TEXT
);

INSERT INTO Event
	(
    name,
    dayEvent,
    initHour,
    finishHour,
    description
	)
	VALUES (
	'Concerto de música clássica',
	'2024-05-30',
	'15:00:00',
	'17:30:00',
	'Um evento inesquicível para os amantes de Chopin'
);

INSERT INTO Event
	(
    name,
    dayEvent,
    initHour,
    finishHour,
    description
	)
	VALUES (
	'Peça: A divina comédia',
	'2024-03-15',
	'20:00:00',
	'22:30:00',
	'Reviva o trajeto que Dante Alighieri fez dentre os infernos, purgatório e céus para reencontrar-se com sua amada.'
);
