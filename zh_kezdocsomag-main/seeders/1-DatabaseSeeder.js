"use strict";

// Faker dokumentáció, API referencia: https://fakerjs.dev/guide/#node-js
const { faker } = require("@faker-js/faker");
const chalk = require("chalk");
const { Group, Student, Teacher } = require("../models");

module.exports = {
    up: async (queryInterface, Sequelize) => {
        const groups = [];
        const groupCount = 10;
        for (let i = 0; i < groupCount; i++) {
            let group = await Group.create({
                name: faker.company.buzzPhrase(),
                workingCommunity: faker.commerce.department(),
            });
            groups.push(group);
        }


        const students = [];
        const studentCount = 100;
        for (let i = 0; i < studentCount; i++) {
            let student = await Student.create({
                name: faker.person.fullName(),
                email: faker.internet.email(),
                semester: faker.number.int({ min: 1, max: 6 }),
                classLetter: faker.string.alpha(),
            });
            students.push(student);

            const statusOptions = ['PENDING', 'ACCEPTED', 'REJECTED'];
            const studentGroups = faker.helpers.arrayElements(groups, faker.number.int({ min: 1, max: 3 }));
            for (let j = 0; j < studentGroups.length; j++) {
                const randomStatus = faker.helpers.arrayElement(statusOptions);
                studentGroups[j].addStudent(student, { through: { status: randomStatus } });
            }
        }

        const teachers = [];
        const teacherCount = 10;
        for (let i = 0; i < teacherCount; i++) {
            let teacher = await Teacher.create({
                name: faker.person.fullName(),
                email: faker.internet.email(),
            });
            teachers.push(teacher);

            const teacherGroups = faker.helpers.arrayElements(groups, faker.number.int({ min: 1, max: 3 }));
            teacher.setGroups(teacherGroups);
        }
    },

    // Erre alapvetően nincs szükséged, mivel a parancsok úgy vannak felépítve,
    // hogy tiszta adatbázist generálnak, vagyis a korábbi adatok enélkül is elvesznek
    down: async (queryInterface, Sequelize) => {},
};
