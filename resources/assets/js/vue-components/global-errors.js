Vue.component('global-errors', {

  data: function() {
    return {
      errors: []
    }
  },

  template: require('./templates/global-errors.html'),

  events: {
    displayGlobalErrors: function(errors) {
      if (typeof errors === 'object') {
          this.errors = _.flatten(_.toArray(errors));
      } else {
          this.errors.push('Un error grave ocurrio. Por favor intente otra vez.');
      }
    }
  }
});