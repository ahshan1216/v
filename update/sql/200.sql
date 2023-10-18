UPDATE `settings` SET `value` = '{\"version\":\"2.0.0\", \"code\":\"200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add user_deletion_reminder tinyint default 0 null;

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('payu', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('paystack', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('razorpay', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('mollie', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('yookassa', '{}');

-- SEPARATOR --

alter table domains drop foreign key domains_vcards_vcard_id_fk;

-- SEPARATOR --

alter table domains  add constraint domains_vcards_vcard_id_fk foreign key (vcard_id) references vcards (vcard_id) on update cascade on delete set null;
