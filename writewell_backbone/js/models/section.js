define([
  'backbone',
  'util'
], function (Backbone, util) {
    var model = Backbone.Model.extend({
        urlRoot: null,
        defaults: {
            id: null,
            text: '',
            templatePlaceholderText: '',
            count: { all: 0, characters: 0, paragraphs: 0, words: 0 }, //all: 52 characters: 47 paragraphs: 1 words: 6
            orderId: null,
            title: '',
            placeholderText: '',
            sources: null, //backbonecollection
            notes: null, //backbonecollection
            color: null
        },
        initialize: function () {
            this.set({color: util.getColorStringForIndex(this.id)});
        },
        parse: function (resp) {
            if (resp.response === undefined) return resp;
            else return resp.response;
        },
        sync: function () {
            return false;
        },
        validate: function (attrs) {

            return null;
        },
        triggerSources: function() {
            this.get('sources').each(function (source) {
                source.trigger('change');
            });
        }
    });
    return model;
});