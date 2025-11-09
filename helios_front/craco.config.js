const path = require("path");

module.exports = {
  style: {
    less: {
      javascriptEnabled: true,
      modifyVars: {
        hack: `true; @import "${path.resolve(
          __dirname,
          "src/styles/variables.less"
        )}";`,
      },
    },
  },
};
