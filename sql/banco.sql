-- Criar tabelas
create table tb_cargo(
	cd_cargo int not null auto_increment,
    nm_cargo varchar(255) not null,
    constraint pk_cargo
		primary key(cd_cargo)
);

create table tb_servico(
	cd_servico int not null auto_increment,
    nm_servico varchar(255) not null,
    constraint pk_servico
		primary key(cd_servico)
);

create table tb_usuario(
	cd_usuario int not null auto_increment,
    nm_usuario varchar(255) not null,
    cd_cargo int not null,
    ds_email varchar(255) not null,
    ds_senha varchar(40) not null,
    ds_imagem varchar(50) not null default 'default.png',
    ic_administrador tinyint not null default 0,
    constraint pk_usuario
		primary key(cd_usuario),
	constraint fk_usuario_cargo
		foreign key(cd_cargo)
			references tb_cargo(cd_cargo)
);

create table tb_relatorio(
	cd_relatorio int not null auto_increment,
    dt_relatorio date not null,
    dt_envio datetime not null,
    cd_usuario int not null,
    qt_pause int,
    constraint pk_relatorio
		primary key(cd_relatorio)
);

create table tb_item_relatorio(
	cd_item_relatorio int not null auto_increment,
    hr_inicio time,
    hr_final time,
    ds_servico varchar(255),
    ds_card varchar(255),
    ds_descricao text,
    cd_relatorio int not null,
    constraint pk_item_relatorio
		primary key(cd_item_relatorio),
	constraint fk_item_relatorio_relatorio
		foreign key(cd_relatorio)
			references tb_relatorio(cd_relatorio)
);

-- Adicionar valores iniciais
insert into tb_cargo(nm_cargo) values
('Programador');

insert into tb_usuario(nm_usuario, cd_cargo, ds_email, ds_senha, ic_administrador) values
('André Seoane', 1, 'andre.seoane@summercomunicacao.com.br', '25f9e794323b453885f5181f1b624d0b', 1);

insert into tb_servico(nm_servico) values
('ABQ'),
('Administradora Independência'),
('AJ Advogados'),
('AnaliaMed'),
('Angel Estética'),
('Apollo Vidros'),
('Art Smile'),
('Asa Branca CFC'),
('BelleVille Construtora'),
('Cellula Mater'),
('Classificados Dentistas'),
('Clínica Bolzan - Dermato'),
('Clínica Bolzan - Oftalmo'),
('Clínica Brandão'),
('Clínica Cauchioli'),
('Clínica Phitris'),
('Clínica Zago'),
('Colégio Notre Dame'),
('Dagan Tubos de Aço'),
('Domínio Imagem'),
('Dr. José Eduardo - Plástica e Beleza'),
('Dr. Diego Astur'),
('Dr. Carlos Sacomani'),
('Dr. Fábricio Dias'),
('Dr. Fermando Almeida'),
('Dr. Fernando Valério'),
('Dr. Nelson Astur'),
('Dr. Otávio Micelli'),
('Dr. Ravenda Moniz'),
('Dr. Renato Alcantara'),
('Dr. Roberto Limongi'),
('Dr. Roger Soares (Dr. Cérebro)'),
('Dr. Thiago Moghetti'),
('Dr. Máira Astur'),
('Endogastro'),
('Escola Pequeno Príncipe'),
('FastFrame'),
('Fazenda Santa Terezinha'),
('Floc'),
('Fortte Log'),
('Funchal'),
('Gabbinetto'),
('Galasso Tintas e Elétrica'),
('Hidromar'),
('Hospital de Olhos Limongi'),
('Hotel Camburi Praia'),
('IBMED'),
('Instituto Astur'),
('Interface Engenharia'),
('IronMaxx'),
('JR Tech'),
('La Mobili'),
('Laboratório Centro Paulista'),
('Lisieux Treinamentos'),
('Mais Coluna'),
('Mar Del Plata'),
('Metal Pan'),
('Metro Arts'),
('Miramar Shopping'),
('Monkeys Beer Pub'),
('Nita Alimentos'),
('Oki & Galli'),
('Oncologistas Associados'),
('Planet Dog'),
('Quantinet'),
('Rainha dos Armarinhos'),
('Residencial Santa Ana'),
('Restaurante Mar Del Plata'),
('Rio Clínica'),
('Sankonfort - Eco Santé'),
('SanMedi'),
('Shopping Diadema'),
('SRO - Radiologia Odontológica'),
('Summer Comunicação'),
('Tecnocal'),
('Time4Fit'),
('Uchikawa Moda Masculina'),
('Uniero'),
('Weleda');