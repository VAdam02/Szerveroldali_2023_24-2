const fastify = require('fastify') ({
    logger: true
})

fastify.get("/hello", async (request, reply) => {
    reply.send("Hello world")
})

fastify.listen({port: 3001}, (err, address) => {
    if (err) throw err
})