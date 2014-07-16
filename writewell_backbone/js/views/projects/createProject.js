define([
	'backbone',
	'data',
	'shared',
	'util',
	'moment',
	'text!templates/projects/createProject.htm',
], function (Backbone, data, shared, util, moment, txtTemplate) {
	var view = Backbone.View.extend({
		initialize: function (options) {
			this.template = _.template(txtTemplate);
		},
		events: {	
			'click #createProjectBtn': 'createProject',
			'click .closeBtn':'close',
			'click .templateDropdown':'setTemplate'
		},
		render: function (e) {
			var self = this;
			//ensure any needed data is loaded...
			self.doRender();
		},
		doRender: function () {

			this.$el.html(this.template({templates: data.templates }));

			return this;
		},
		selectedTemplateId: 0,
		setTemplate: function (e) {
			this.selectedTemplateId = parseInt($(e.currentTarget).attr('value'));
			console.log('template id: ' + this.selectedTemplateId);
			$('#templatePlaceholder').html($(e.currentTarget).html());
		},
		createProject: function () {
			//TODO get template id
            var template=data.templates.where({ tid: this.selectedTemplateId })[0].clone();
			//var template = data.templates.get(this.selectedTemplateId).clone();
			var title = $('#projectTitle').val();
			if (title.length === 0) title = template.get('title');
			var id = data.projects.length + 1;
			template.set({ id: id, title: title, createdAt: moment() }); //increment
			data.projects.add(template);
			console.log('template added to projects');
			this.close();

			console.log('selectThisProject');
			data.currentProject = data.projects.get(id);
            if(this.selectedTemplateId == 1){
                data.currentProject.isBlank = true;
            }
			$('.nav-links li').removeClass('active');
			shared.router.navigate('home',true);		
		},
 		close: function () {
 			shared.closeModal(this);
 		}
	});
	return view;
});