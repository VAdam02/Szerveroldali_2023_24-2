'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Group extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.belongsToMany(models.Student, {
        through: models.GroupStudent
      })

      this.belongsToMany(models.Teacher, {
        through: "GroupTeacher"
      })
    }
  }
  Group.init({
    name: DataTypes.STRING,
    workingCommunity: DateType.STRING
  }, {
    sequelize,
    modelName: 'Group',
  });
  return Group;
};
