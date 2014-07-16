define([
  'backbone',
  'util',
  'moment'
], function (Backbone, util, moment) {
    var model = Backbone.Model.extend({
        urlRoot: '../writewell_production/public/project/',
        defaults: {
            id: null,
            tid:null,
            sections: null, //backbone collection
            sources: null, //backbone collection
            notes: null, //backbone collection
            title: '',
            wordCount: 0,
            pageCount: 0,
            createdAt: null,
            color: null
        },
        initialize: function () {
            this.set({createdAt: moment(), color: util.getColorStringForIndex(this.id) });
        },
        parse: function (resp) {
            if (resp.response === undefined) return resp;
            else return resp.response;
        },
        validate: function (attrs) {

            return null;
        },
        wordCount: function () {
        	if (this.get('sections')) {
        		return util.sumCollectionAttribute(this.get('sections'), 'count.words', false);
        	} else {
        		console.log('bad sections collection on project')
        		return 0;
        	}
        }
    });
    return model;
});