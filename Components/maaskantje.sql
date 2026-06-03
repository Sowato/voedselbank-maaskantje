create database maaskantje;
category: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

code: varchar(25) NN

omschrijving: varchar(255) NN

+ keys

#1: PK (id) (underlying index PRIMARY)

inhoud: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

pakket_id: int(11) NN

product_id: int(11) NN

aantal: double NN

+ indices

IDX_1DB54D1ECDE6430C: index (pakket_id) type btree

IDX_1DB54D1E4584665A: index (product_id) type btree

+ keys

#1: PK (id) (underlying index PRIMARY)

+ foreign-keys

FK_1DB54D1ECDE6430C: foreign key (pakket_id) -> pakket (id)

FK_1DB54D1E4584665A: foreign key (product_id) -> product (id)

klant: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

email: varchar(255) NN

gezins_naam: varchar(255) NN

plaats: varchar(255)

adres: varchar(255)

telefoon: varchar(255) NN

volwassen: int(11) NN

kind: int(11)

baby: int(11)

+ keys

#1: PK (id) (underlying index PRIMARY)

klant_wens: table collate utf8mb4_unicode_ci

+ columns

klant_id: int(11) NN

wens_id: int(11) NN

+ indices

IDX_176492503C427B2F: index (klant_id) type btree

IDX_176492502A12754E: index (wens_id) type btree

+ keys

#1: PK (klant_id, wens_id) (underlying index PRIMARY)

+ foreign-keys

FK_176492503C427B2F: foreign key (klant_id) -> klant (id) d:cascade

FK_176492502A12754E: foreign key (wens_id) -> wens (id) d:cascade

leverancier: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

company: varchar(255) NN

adres: varchar(255)

plaats: varchar(255)

contact_persoon: varchar(255) NN

email: varchar(255) NN

telefoon: varchar(25) NN

volgende_levering_datum: datetime NN

+ keys

#1: PK (id) (underlying index PRIMARY)

levering: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

user_id: int(11) NN

product_id: int(11) NN

leverancier_id: int(11) NN

datumtijd: datetime NN

aantal: double NN

houdbaar_tot: datetime NN

+ indices

IDX_19D93554A76ED395: index (user_id) type btree

IDX_19D935544584665A: index (product_id) type btree

IDX_19D935546E3FE6C9: index (leverancier_id) type btree

+ keys

#1: PK (id) (underlying index PRIMARY)

+ foreign-keys

FK_19D93554A76ED395: foreign key (user_id) -> user (id)

FK_19D935544584665A: foreign key (product_id) -> product (id)

FK_19D935546E3FE6C9: foreign key (leverancier_id) -> leverancier (id)

messenger_messages: table collate utf8mb4_unicode_ci

+ columns

id: bigint(20) NN auto_increment = 1

body: longtext NN

headers: longtext NN

queue_name: varchar(190) NN

created_at: datetime NN

available_at: datetime NN

delivered_at: datetime

+ indices

IDX_75EA56E0FB7336F0: index (queue_name) type btree

IDX_75EA56E0E3BD61CE: index (available_at) type btree

IDX_75EA56E016BA31DB: index (delivered_at) type btree

+ keys

#1: PK (id) (underlying index PRIMARY)

pakket: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

user_id: int(11) NN

klant_id: int(11) NN

datum: datetime NN

uitgifte_datum: datetime

+ indices

IDX_F9F58C9BA76ED395: index (user_id) type btree

IDX_F9F58C9B3C427B2F: index (klant_id) type btree

+ keys

#1: PK (id) (underlying index PRIMARY)

+ foreign-keys

FK_F9F58C9BA76ED395: foreign key (user_id) -> user (id)

FK_F9F58C9B3C427B2F: foreign key (klant_id) -> klant (id)

product: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

category_id: int(11)

streepjescode: varchar(255) NN

omschrijving: varchar(255) NN

aantal: double

+ indices

IDX_D34A04AD12469DE2: index (category_id) type btree

+ keys

#1: PK (id) (underlying index PRIMARY)

+ foreign-keys

FK_D34A04AD12469DE2: foreign key (category_id) -> category (id)

user: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

email: varchar(180) NN

roles: longtext NN collate utf8mb4_bin

password: varchar(255) NN

+ keys

#1: PK (id) (underlying index PRIMARY)

UNIQ_8D93D649E7927C74: AK (email)

+ checks

#1: check (json_valid(`roles`)) cols = [roles]

wens: table collate utf8mb4_unicode_ci

+ columns

id: int(11) NN auto_increment = 1

omschrijving: varchar(255) NN

+ keys

#1: PK (id) (underlying index PRIMARY)