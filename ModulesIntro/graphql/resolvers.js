module.exports = {
    Query: {
        hello: async () => {
            return "Hello world"
            //return null //hiba lesz az eredmény
        },
        hello2: async (_, {name}) => `Hello ${name}`,
        isEven: async (_, {num}) => num % 2 === 0
    }
}