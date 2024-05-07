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