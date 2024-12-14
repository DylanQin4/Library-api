INSERT INTO author (first_name, last_name, birth_date, biography) VALUES
    ('J.K.', 'Rowling', '1965-07-31', 'British author, best known for the Harry Potter series.'),
    ('George', 'Orwell', '1903-06-25', 'English novelist, essayist, journalist and critic.');

INSERT INTO category (name) VALUES
    ('Fantasy'),
    ('Science Fiction'),
    ('Literature');

INSERT INTO book (author_id, title, publication_date, number_pages) VALUES
    (1, 'Harry Potter and the Sorcerer''s Stone', '1997-06-26', 223),
    (1, 'Harry Potter and the Chamber of Secrets', '1998-07-02', 251),
    (2, '1984', '1949-06-08', 328),
    (2, 'Animal Farm', '1945-08-17', 112);

INSERT INTO roles (name) VALUES
    ('USER'),
    ('ADMIN');