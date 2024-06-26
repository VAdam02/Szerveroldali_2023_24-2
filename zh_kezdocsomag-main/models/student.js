'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Student extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.belongsToMany(models.Group, {
        through: models.GroupStudent
      })
    }
  }
  Student.init({
    name: DataTypes.STRING,
    email: DataTypes.STRING,
    semester: DataTypes.INTEGER,
    classLetter: DataTypes.STRING
  }, {
    sequelize,
    modelName: 'Student',
  });
  return Student;
};
