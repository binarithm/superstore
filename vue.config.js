var extractCSS = {
  extract: {
    filename: 'css/[name].css',
    chunkFilename: 'css/chunks/[name].css',
  },
}

module.exports = {
  devServer: {
    disableHostCheck: true,
  },

  transpileDependencies: ['vuetify', '@peepi/vuetify-tiptap'],
  outputDir: 'assets',
  filenameHashing: false,
  productionSourceMap: false,

  chainWebpack: config => {
    config.optimization.delete('splitChunks')

    // Remove the standard entry point
    config.entryPoints.delete('app')

    // Separate output directory for helpers/vendors and custom files
    config.output.filename('js/[name].js')
    config.output.chunkFilename('js/chunks/[name].js')

    config
    .entry('superstore-admin-dashboard')
      .add('@/admin-dashboard/main.js')
      .end()

    .entry('superstore-seller-login')
      .add('@/seller-login/main.js')
      .end()

    .entry('superstore-seller-dashboard')
      .add('@/seller-dashboard/main.js')
      .end()

    .entry('superstore-stores')
      .add('@/stores/main.js')
      .end()
  },

  // Separate css folder.
  css: process.env.NODE_ENV === 'production' ? extractCSS : {},
}
