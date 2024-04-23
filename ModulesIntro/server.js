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

fastify.get("/posts/:id", async (request, reply) => {
    const post = await Post.findByPk(request.params.id)

    if (!post) {
        reply.code(404).send({message: "Post not found"})
    }

    reply.send(post)
})

fastify.listen({port: 3001}, (err, address) => {
    if (err) throw err
})