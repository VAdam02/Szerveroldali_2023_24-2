'use strict';

const { faker } = require('@faker-js/faker');
const { Category, Post, User } = require("../models")

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    const users = [];
    const userCount = Math.floor(Math.random() * 15) + 15; //[15, 30)
    for (let i = 0; i < userCount; i++) {
      let user = await User.create({
        name: faker.person.fullName(),
        email: "email" + i + "@szerveroldali.hu",
        age: Math.floor(Math.random() * 50) + 18, //[18, 68)
        phone: Math.random() > 0.3 ? faker.phone.number()  : null,
        password: "password" + i,
        isadmin: Math.random() > 0.9
      })
      users.push(user);
    }

    const categories = [];
    const categoryCount = Math.floor(Math.random() * 10) + 10; //[10, 20)
    for (let i = 0; i < categoryCount; i++) {
      let category = await Category.create({
        name: faker.word.adverb(),
        color: faker.color.rgb()
      })
      categories.push(category);
    }

    const posts = [];
    const postCount = Math.floor(Math.random() * 20) + 30; //[30, 50)
    for (let i = 0; i < postCount; i++) {
      let post = await Post.create({
        title: faker.lorem.sentence(),
        content: faker.lorem.paragraphs({ min: 3, max: 6 }),
        published: Math.random() > 0.2,
        authorId: faker.helpers.arrayElement(users).id
      })

      post.setCategories(faker.helpers.arrayElements(categories, {min: 1, max: 5 }));

      posts.push(post);
    }
  },

  async down (queryInterface, Sequelize) {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
  }
};
