'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.createTable('CategoryPost', {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER
      },
      categoryId: {
        type: Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: "Categories",
          key: "id"
        }
      },
      postId: {
        type: Sequelize.INTEGER,
        allowNull: false,
        references: {
          model: "Posts",
          key: "id"
        }
      }
    });

    await queryInterface.addConstraint('CategoryPost', {
      fields: ["categoryId", "postId"],
      type: "unique",
    })
  },

  async down (queryInterface, Sequelize) {
     await queryInterface.dropTable('CategoryPost');
  }
};
