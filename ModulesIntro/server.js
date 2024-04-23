const fastify = require('fastify') ({
    logger: true
})

const { User, Post, Category } = require("./models")

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
    }

    await post.update(request.body)
    post.setCategories(request.body.categories)
    reply.send(post)
})

fastify.listen({port: 3001}, (err, address) => {
    if (err) throw err
})