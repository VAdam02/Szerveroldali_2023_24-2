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

/*
Létrehoz egy új diákot a kérés törzsében (body) megadott adatokkal. A végpont hitelesített, tehát csak bejelentkezett felhasználók használhatják. Ezen felül jogosultságkezelést is kell végezni: a végpontot csak egy tanár hívhatja meg. Ha a JWT token payload-jában megadott e-mail címmel nem létezik tanár, akkor 403 FORBIDDEN státuszkódot kell visszaadni.
*/

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
        return reply.code(403);
    }

    const { name, email, classData } = request.body;

    let classLetter = classData.slice(-1);
    let semester = classData.split(".")[0];

    if (await Student.findOne({ where: { email }})) {
        return reply.code(409);
    }

    const student = await Student.create({ name, email, semester, classLetter});

    return reply.code(201).send(student); //TODO valamiért ez a sor túl lassú
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
