<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250919161457 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE bell (id BINARY(16) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('alter table bell
            add constraint bell__name
            unique (name);');
        // TODO ADD col order = 1-6

        $this->addSql('INSERT INTO bell (id, name) VALUES (UUID_TO_BIN(UUID()), "1 урок")');
        $this->addSql('INSERT INTO bell (id, name) VALUES (UUID_TO_BIN(UUID()), "2 урок")');
        $this->addSql('INSERT INTO bell (id, name) VALUES (UUID_TO_BIN(UUID()), "3 урок")');
        $this->addSql('INSERT INTO bell (id, name) VALUES (UUID_TO_BIN(UUID()), "4 урок")');
        $this->addSql('INSERT INTO bell (id, name) VALUES (UUID_TO_BIN(UUID()), "5 урок")');
        $this->addSql('INSERT INTO bell (id, name) VALUES (UUID_TO_BIN(UUID()), "6 урок")');

        # teacher
        $this->addSql('CREATE TABLE teacher (id BINARY(16) NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('alter table teacher
            add constraint teacher__name
            unique (name);');


        $teacherV = 'd19e5f7e-9619-11f0-8c84-a2b04ff57eee';
        $teacherP = 'd4692f2d-9619-11f0-8c84-a2b04ff57eee';
        $teacherD = 'd782860a-9619-11f0-8c84-a2b04ff57eee';
        $this->addSql('INSERT INTO teacher (id, name) VALUES (UUID_TO_BIN("' . $teacherV . '"), "Володимир")');
        $this->addSql('INSERT INTO teacher (id, name) VALUES (UUID_TO_BIN("' . $teacherP . '"), "Павло")');
        $this->addSql('INSERT INTO teacher (id, name) VALUES (UUID_TO_BIN("' . $teacherD . '"), "Дмитро")');

        #subject
        $this->addSql('CREATE TABLE subject (id BINARY(16) NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('alter table subject
            add constraint subject__name
            unique (name);');

        $subjectMath = '9a886033-9619-11f0-8c84-a2b04ff57eee';
        $subjectUL = 'a8ac9b41-9619-11f0-8c84-a2b04ff57eee';
        $subjectSport = 'b32fc0fe-9619-11f0-8c84-a2b04ff57eee';
        $subjectEL = 'bab91cef-9619-11f0-8c84-a2b04ff57eee';
        $this->addSql('INSERT INTO subject (id, name) VALUES (UUID_TO_BIN("' . $subjectMath . '"), "Математика")');
        $this->addSql('INSERT INTO subject (id, name) VALUES (UUID_TO_BIN("' . $subjectUL . '"), "Українська мова")');
        $this->addSql('INSERT INTO subject (id, name) VALUES (UUID_TO_BIN("' . $subjectSport . '"), "Фізкультура")');
        $this->addSql('INSERT INTO subject (id, name) VALUES (UUID_TO_BIN("' . $subjectEL . '"), "Англійська мова")');

        #TeacherSubject
        $this->addSql('CREATE TABLE teacher_subject (teacher_id BINARY(16) NOT NULL, subject_id BINARY(16) NOT NULL, PRIMARY KEY(teacher_id, subject_id))');
        // TODO add FOREIGN KEY teacher_id
        // TODO add FOREIGN KEY subject_id

        $this->addSql('INSERT INTO teacher_subject (teacher_id, subject_id) VALUES (UUID_TO_BIN("' . $teacherV . '"), UUID_TO_BIN("' . $subjectMath . '"))');
        $this->addSql('INSERT INTO teacher_subject (teacher_id, subject_id) VALUES (UUID_TO_BIN("' . $teacherP . '"), UUID_TO_BIN("' . $subjectUL . '"))');
        $this->addSql('INSERT INTO teacher_subject (teacher_id, subject_id) VALUES (UUID_TO_BIN("' . $teacherP . '"), UUID_TO_BIN("' . $subjectEL . '"))');
        $this->addSql('INSERT INTO teacher_subject (teacher_id, subject_id) VALUES (UUID_TO_BIN("' . $teacherD . '"), UUID_TO_BIN("' . $subjectSport . '"))');
        $this->addSql('INSERT INTO teacher_subject (teacher_id, subject_id) VALUES (UUID_TO_BIN("' . $teacherV . '"), UUID_TO_BIN("' . $subjectEL . '"))');

        #school_class
        $this->addSql('CREATE TABLE school_class (id BINARY(16) NOT NULL, name VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('alter table school_class
            add constraint school_class__name
            unique (name);');

        $schoolClassA = 'c022c377-9619-11f0-8c84-a2b04ff57eee';
        $schoolClassB = 'c3382a35-9619-11f0-8c84-a2b04ff57eee';
        $schoolClassC = 'cb5d3e00-9619-11f0-8c84-a2b04ff57eee';
        $this->addSql('INSERT INTO school_class (id, name) VALUES (UUID_TO_BIN("' . $schoolClassA . '"), "1-A")');
        $this->addSql('INSERT INTO school_class (id, name) VALUES (UUID_TO_BIN("' . $schoolClassB . '"), "1-B")');
        $this->addSql('INSERT INTO school_class (id, name) VALUES (UUID_TO_BIN("' . $schoolClassC . '"), "1-C")');

        #Curriculum
        $this->addSql('CREATE TABLE curriculum (
            id BINARY(16) NOT NULL,
            subject_id BINARY(16) NOT NULL,
            school_class_id BINARY(16) NOT NULL,
            hours_per_year SMALLINT NOT NULL,
            PRIMARY KEY(id))');

        $this->addSql('alter table curriculum
            add constraint curriculum__subject_id__school_class_id
            unique (subject_id, school_class_id);');

        // TODO add FOREIGN KEY subject_id
        // TODO add FOREIGN KEY school_class_id

        $this->addSql(sprintf('INSERT INTO curriculum (id, subject_id, school_class_id, hours_per_year)
            VALUE (UUID_TO_BIN(UUID()), UUID_TO_BIN("%s"), UUID_TO_BIN("%s"), %d);', $subjectMath, $schoolClassA, 64));

        $this->addSql(sprintf('INSERT INTO curriculum (id, subject_id, school_class_id, hours_per_year)
            VALUE (UUID_TO_BIN(UUID()), UUID_TO_BIN("%s"), UUID_TO_BIN("%s"), %d);', $subjectUL, $schoolClassA, 105));

        $this->addSql(sprintf('INSERT INTO curriculum (id, subject_id, school_class_id, hours_per_year)
            VALUE (UUID_TO_BIN(UUID()), UUID_TO_BIN("%s"), UUID_TO_BIN("%s"), %d);', $subjectMath, $schoolClassB, 35));

        $this->addSql(sprintf('INSERT INTO curriculum (id, subject_id, school_class_id, hours_per_year)
            VALUE (UUID_TO_BIN(UUID()), UUID_TO_BIN("%s"), UUID_TO_BIN("%s"), %d);', $subjectSport, $schoolClassB, 64));

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bell');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE teacher_subject');
        $this->addSql('DROP TABLE school_class');
        $this->addSql('DROP TABLE curriculum');
    }
}
