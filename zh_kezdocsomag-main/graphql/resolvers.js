const auth = require("./auth");
const db = require("../models");
const { Sequelize, sequelize } = db;
const { ValidationError, DatabaseError, Op } = Sequelize;
// TODO: Importáld a modelleket
const { Teacher, Student, Group } = db;

module.exports = {
    Query: {
        // Elemi Hello World! példa:
        //helloWorld: () => "Hello World!",

        // Példa paraméterezésre:
        //helloName: (_, { name }) => `Hello ${name}!`,

        classes: async () => {
            const students = await Student.findAll();
            return students.sort((a, b) => a.semester - b.semester || !a.classLetter.localeCompare(b.classLetter)).map(student => `${student.semester}. ${student.classLetter}`);
        }
    },
};
