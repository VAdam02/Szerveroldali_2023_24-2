const { Category, Post, User } = require("../models");

module.exports = {
    Query: {
        hello: async () => {
            return "Hello world"
            //return null //hiba lesz az eredmény
        },
        hello2: async (_, {name}) => `Hello ${name}`,
        isEven: async (_, {num}) => num % 2 === 0,
        categories: async () => {
            return Category.findAll();
        },
        posts: async () => {
            return Post.findAll();
        },
        categoryById: async (_, {id}) => {
            //return Category.findByPk(id);
            if (id == null) return null;
            return Category.findByPk(id);
        },
        statistics: async () => {
            return {
                userCount: await User.count(),
                postCount: await Post.count(),
                postPerUser: await Post.count() / await User.count()
            };
        }
    },

    Mutation: {
        createCategory1: async (_, {name, color}) => {
            return Category.create({name, color});
        },
        createCategory2: async (_, { input }) => {
            return Category.create(input);
        },
        createPost: async (_, {input} ) => {
            if (input.date == null) {
                input.date = new Date();
            }
            if (input.published == null) {
                input.published = true;
            }
            const newPost = await Post.create(input);
            await newPost.setCategories(input.categories);
            return newPost;
        }
    },

    Category: {
        dummy: async () => {
            return "dummy"
        },
        posts: async (category) => {
            return category.getPosts();
        }
    },
    Post: {
        categories: async (post) => {
            return post.getCategories();
        },
        user: async (post) => {
            return post.getUser();
        }
    }
}