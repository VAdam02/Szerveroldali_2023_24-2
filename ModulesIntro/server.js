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

fastify.listen({port: 3001}, (err, address) => {
    if (err) throw err
})