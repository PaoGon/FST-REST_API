CREATE TABLE accounts(
    acc_id SERIAL PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR NOT NULL,
    gender VARCHAR(1) NOT NULL,
    is_admin BOOLEAN NOT NULL,
    profile_dir VARCHAR DEFAULT concat('url/storage/', currval('accounts_acc_id_seq'))
);


CREATE TABLE teachers(
    acc_id SERIAL PRIMARY KEY,
    position VARCHAR,
    total_credits INT DEFAULT 0,
    CONSTRAINT fk_accounts
        FOREIGN KEY(acc_id)
            REFERENCES accounts(acc_id) ON DELETE CASCADE
);


CREATE TABLE services(
    service_id SERIAL PRIMARY KEY,
    event_name VARCHAR NOT NULL,
    starting_date DATE NULL,
    ending_date DATE NULL,
    venue VARCHAR(13) NULL,
    sponsor VARCHAR NULL,
    level_of_event VARCHAR NULL,
    credit_point INT DEFAULT 0,
    created_at DATE NOT NULL DEFAULT NOW(),
    service_dir VARCHAR GENERATED ALWAYS as('url/storage/' || cast(teacher_id as text)|| '/service/' || cast(service_id as text)) stored,
    teacher_id INT NOT NULL REFERENCES teachers(acc_id) ON DELETE CASCADE
);
