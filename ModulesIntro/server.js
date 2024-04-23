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

fastify.post("/posts", async (request, reply) => {
    console.log(request.body)

    if (request.body.published == null) {
        request.body.published = true
    }

    request.body.date = new Date()
    const post = await Post.create(request.body)

    post.setCategories(request.body.categoryId)

    reply.code(201).send(post)
})

fastify.listen({port: 3001}, (err, address) => {
    if (err) throw err
})