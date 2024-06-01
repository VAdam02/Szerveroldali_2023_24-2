require("dotenv").config();
const fastify = require("fastify")({
    logger: {
        level: "info",
        file: "fastify.log",
    },
});
const autoload = require("@fastify/autoload");
const chalk = require("chalk");
const AutoTester = require("./test/inject");
const { join } = require("path");
const registerGraphQL = require("./graphql");

const port = process.env.PORT || 4000;
const secret = process.env.JWT_SECRET || "secret";

const { Teacher, Group, Student } = require("./models");

// Hitelesítés
fastify.register(require("@fastify/jwt"), {
    secret,
});

fastify.decorate("auth", async function (request, reply) {
    try {
        await request.jwtVerify();
    } catch (err) {
        reply.send(err);
    }
});

//3. feladat
fastify.post("/integers", {
    schema: {
        body: {
            type: "array",
        }
    }
}, async (request, reply) => {
    const { body } = request;
    const processedIntegers = body.filter(e => Number.isInteger(e)).map(e => e % 2 === 0 ? e : e * 2);
    const notIntegers = body.filter(e => !Number.isInteger(e));
    const randomInteger = processedIntegers.length > 0 ? processedIntegers[Math.floor(Math.random() * processedIntegers.length)] : null;

    if (processedIntegers.length === 0) {
        return { processedIntegers, notIntegers, randomInteger, random: null };
    }
    else {
        return { processedIntegers, notIntegers, randomInteger };
    }
});

//4. feladat
fastify.get("/groups", async (request, reply) => {
    const groups = await Group.findAll({
        include: [
            {
                model: Teacher,
                attributes: ["name"],
                through: {
                    attributes: []
                }
            },
            {
                model: Student,
                attributes: ["name"],
                through: {
                    attributes: [],
                    where: {
                        status: "ACCEPTED"
                    }
                }
            }
        ]
    });

    return groups;
});

//5. feladat
fastify.post("/teacher/create-student", {
    onRequest: [fastify.auth],
    schema: {
        body: {
            type: "object",
            required: ["name", "email", "classData"],
            properties: {
                name: { type: "string" },
                email: { type: "string", format: "email" },
                classData: { type: "string" }
            }
        }
    }
}, async (request, reply) => {
    const teacher = await Teacher.findOne({ where: { email: request.user.email }});
    if (!teacher) {
        return reply.code(403).send();
    }

    const { name, email, classData } = request.body;

    const semester = classData.split(".")[0];
    const classLetter = classData.split(".")[1].toUpperCase();

    if (await Student.findOne({ where: { email }})) {
        return reply.code(409).send();
    }

    const student = await Student.create({ name, email, semester, classLetter});

    return reply.code(201).send(student);
})

// GraphQL regisztrálása (mercurius modul)
registerGraphQL(fastify);

// Route-ok automatikus betöltése
fastify.register(autoload, {
    dir: join(__dirname, "routes"),
});

// App indítása a megadott porton
fastify.listen({ port }, (err, address) => {
    if (err) throw err;

    console.log(`A Fastify app fut: ${chalk.yellow(address)}`);
    console.log(`GraphQL végpont: ${chalk.yellow(`${address}/graphql`)}`);
    console.log(`GraphiQL végpont: ${chalk.yellow(`${address}/graphiql`)}`);

    // FONTOS! Erre szükség van, hogy az automata tesztelő megfelelően tudjon inicializálni!
    // Ehhez a sorhoz ne nyúlj a munkád közben: hagyd legalul, ne vedd ki, ne kommenteld ki,
    // különben elrontod az automata tesztelő működését!
    AutoTester.handleStart();
});
