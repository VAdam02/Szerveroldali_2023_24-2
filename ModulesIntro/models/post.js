'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Post extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.belongsTo(models.User, { foreignKey: 'authorId' })
      this.belongsToMany(models.Category, { through: 'CategoryPost', foreignKey: 'postId'})
    }
  }
  Post.init({
    title: DataTypes.STRING,
    content: DataTypes.STRING,
    date: DataTypes.DATE,
    published: DataTypes.BOOLEAN,
    authorId: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Post',
  });
  return Post;
};