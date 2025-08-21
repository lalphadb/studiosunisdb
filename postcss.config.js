export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
    ...(process.env.NODE_ENV === 'production' ? { 
      cssnano: {
        preset: ['advanced', {
          discardComments: {
            removeAll: true,
          },
          reduceIdents: false,
          zindex: false,
          autoprefixer: false,
        }]
      } 
    } : {})
  },
}
