const fastify = require('fastify') ({
    logger: true
})

const { User, Post, Category } = require("./models")
const { Op } = require("sequelize")

fastify.get("/hello", async (request, reply) => {
    reply.send("Hello world")
})

fastify.get("/posts", async (request, reply) => {
    reply.send(await Post.findAll())
})

fastify.get("/posts/:id", {
    schema: {
        params: {
            id: { type: "integer"}
        }
    }
}, async (request, reply) => {
    const post = await Post.findByPk(request.params.id)

    if (!post) {
        reply.code(404).send({message: "Post not found"})
        return;
    }

    reply.send(post)
})

fastify.post("/posts", {
    schema: {
        body: {
            type: "object",
            required: ["title", "content", "authorId"],
            properties: {
                title: { type: "string" },
                content: { type: "string" },
                authorId: { type: "integer" },
                categories: { type: "array", default: [], items: { type: "integer" } },
                published: { type: "boolean", default: true}
            }
        }
    }
}, async (request, reply) => {
    request.body.date = new Date()
    const post = await Post.create(request.body)

    post.setCategories(request.body.categories)

    reply.code(201).send(post)
})

fastify.put("/posts/:id", {
    schema: {
        params: {
            id: { type: "integer"}
        },
        body: {
            type: "object",
            required: ["title", "content", "authorId"],
            properties: {
                title: { type: "string" },
                content: { type: "string" },
                authorId: { type: "integer" },
                categories: { type: "array", default: [], items: { type: "integer" } },
                published: { type: "boolean", default: true}
            }
        }
    }
}, async (request, reply) => {
    const post = await Post.findByPk(request.params.id)
    if (!post) {
        reply.code(404).send({message: "Post not found"})
        return;
    }

    await post.update(request.body)
    post.setCategories(request.body.categories)
    reply.send(post)
})

fastify.patch("/posts/:id", {
    schema: {
        params: {
            id: { type: "integer"}
        },
        body: {
            type: "object",
            properties: {
                title: { type: "string" },
                content: { type: "string" },
                authorId: { type: "integer" },
                categories: { type: "array", items: { type: "integer" } },
                published: { type: "boolean"}
            }
        }
    }
}, async (request, reply) => {
    const post = await Post.findByPk(request.params.id)
    if (!post) {
        reply.code(404).send({message: "Post not found"})
        return;
    }

    await post.update(request.body)
    post.setCategories(request.body.categories)
    reply.send(post)
})

fastify.delete("/posts/:id", {
    schema: {
        params: {
            id: { type: "integer" }
        }
    }
}, async (request, reply) => {
    const post = await Post.findByPk(request.params.id);
    post.destroy();
})

fastify.get("/unpublished", async (request, reply) => {
    reply.send(await Post.findAll({ where: {published: false }}))
})

fastify.get("/published", async (request, reply) => {
    reply.send(await Post.findAll({ where: {published: true }}))
})

fastify.get("/testaccount1", async (request, reply) => {
    reply.send(await User.findAll({ where: {
        id: { [Op.lt]: 10}
    }}))
})

fastify.get("/testaccount2", async (request, reply) => {
    reply.send(await User.findAll({
        where: {
            //id: { [Op.between]: [10, 20]} //10, 11, 12 ... 19, 20
            id: { [Op.gt]: 5, [Op.lt]: 10} //6, 7, 8, 9
        }
    }))
})

fastify.get("/testaccount3", async (request, reply) => {
    reply.send(await User.findAll({
        where: {
            id: {
                [Op.or]: [
                    { [Op.lt]: 5 },
                    { [Op.gt]: 10}
                ]
            }
        },
        order: [
            [["id", "DESC"]]
        ],
        limit: 5
    }))
})

fastify.get("/posts/:id/categories", {
    schema: {
        params: {
            id: { type: "integer" }
        }
    }
}, async (request, reply) => {
    const post = await Post.findByPk(request.params.id)
    if (!post) {
        reply.code(404).send({message: "Post not found"})
        return;
    }

    reply.send(await post.getCategories())
})

fastify.get("/post-without-time", async (request, reply) => {
    reply.send(await Post.findAll({
        //attributes: ["id", "title", "content", "authorId"]
        attributes: { exclude: ["createdAt", "updatedAt", "published", "date"]}
    }))
})

fastify.get("/posts-with-user", async (request, reply) => {
    reply.send(await Post.findAll({ include: User}))
})

fastify.get("/posts-with-category-1", async (request, reply) => {
    reply.send(await Post.findAll({ include: Category }))
})

fastify.get("/posts-with-category-2", async (request, reply) => {
    reply.send(await Post.findAll({ include: { model: Category }}))
})

fastify.get("/posts-with-category-3", async (request, reply) => {
    reply.send(await Post.findAll({ include: { model: Category, through: { attributes: [] } }}))
})

fastify.get("/posts-with-everything", async (request, reply) => {
    reply.send(await Post.findAll({ include: [
        User,
        { model: Category, through: { attributes: [] }}
    ]}))
})

fastify.register(require("@fastify/jwt"), {
    secret: "nagyonTitkosJelszo"
})

fastify.decorate("auth", async (request, reply) => {
    try {
        await request.jwtVerify();
    } catch (err) {
        reply.send(err);
    }
})

fastify.post("/login", {
    schema: {
        body: {
            type: "object",
            required: ['email', 'password'],
            properties: {
                email: { type: "string" },
                password: { type: "string" }
            }
        }
    }
}, async (request, reply) => {
    const user = await User.findOne({ where: { email: request.body.email }})
    if (!user) {
        reply.code(404).send({message: "No such user"})
        return;
    }

    if (user.password != request.body.password) {
        reply.code(401).send({message: "Wrong password"})
        return;
    }

    reply.send({ token: fastify.jwt.sign(user.toJSON())}) //itt megadod, hogy tárolja el a user-t
})

fastify.get("/protected", { onRequest: [fastify.auth] }, async (request, reply) => {
    const user = await User.findByPk(request.user.id); //itt lekéred a user-t

    if (!user) {
        reply.code(404).send({message: "No such user"})
        return;
    }

    reply.send(await user.getPosts())
})

const mercurius = require("mercurius")

const { readFileSync } = require("fs")
const schema = readFileSync("./graphql/schema.gql").toString()
const resolvers = require("./graphql/resolvers")

fastify.register(mercurius, {
    schema,
    resolvers,
    graphiql: true,
    path: "/graphql"
})

fastify.listen({port: 3001}, (err, address) => {
    if (err) throw err
})