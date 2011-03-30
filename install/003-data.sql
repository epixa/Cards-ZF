insert into user_group (id, `name`, code) values
    (1, 'Admin', 'admin'),
    (2, 'Verified', 'verified'),
    (3, 'Unverified', 'unverified');

insert into `user` (id, `alias`) values
    (1, 'testusr');

insert into user_group_assoc (user_id, group_id) values
    (1, 1);

insert into user_auth (user_id, login_id, pass_hash, is_temporary_pass) values
    (1, 'testusr', '$2a$08$8uHd26d5YtE8Q6nn91DUJ.C/peuhIk.5u5y1g.fg.cFKROPjG7UcO', false);

insert into user_profile (user_id, email) values
    (1, 'test@epixa.com');