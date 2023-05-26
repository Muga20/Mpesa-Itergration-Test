import sequelize  from "sequelize";

const db = new sequelize('Stkrequest', 'root', '' , {
    host: "localhost",
    dialect: "mysql"
});

export default db