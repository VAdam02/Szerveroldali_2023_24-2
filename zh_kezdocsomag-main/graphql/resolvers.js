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
        },

        getGroupRelatedStudentsFromClass: async (_, { semester, classLetter }) => {
            classLetter = classLetter.length == 1 ? classLetter.toUpperCase() : null;
            semester = parseInt(semester);
            semester = 12 >= semester && semester >= 1 ? semester : null;
            if (!classLetter || !semester) {
                throw new Error("Invalid className");
            }

            const students = await Student.findAll({
                where: {
                    semester,
                    classLetter
                },
                include: {
                    model: Group,
                    through: {
                        where: {
                            status: "ACCEPTED"
                        }
                    },
                    attributes: []
                },
                attributes: ["name"]
            })

            return students.map(student => student.name).sort();
        }
    },
};
