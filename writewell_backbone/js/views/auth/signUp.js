define([
	'backbone',
	'shared',
	'data',
	'text!templates/auth/signUp.htm',
	'jqueryui'
], function (Backbone, shared, data, txtTemplate, jqueryui) {
	var mainHomeView = Backbone.View.extend({
		initialize: function () {
			this.template = _.template(txtTemplate);
		},
		events: {
			'click #joinBtn': 'join',
			'click .button-gender': 'toggleActive',
			'click .templateDropdown':'setTemplate'
		},
		render: function () {
			var self = this;
			data.ensureDemoDataLoaded(function () {
				self.doRender();
			});
		},
		doRender: function () {
			this.$el.html(this.template());
			$('#slider-container').slider({min: 13, max: 100, step: 1, value: 20});
            $('.ui-slider-handle .ui-state-default .ui-corner-all').css("left","0")
			$('#slider-container').slider({
				slide: function (event, ui) {
					var val = ui.value;
					$('#age').val(val);
				}
			})

		},
		join: function () {
			console.log('clicked join');




                var gender=null;
                if($("#male").hasClass('active'))
                 gender='male';
                else if($("#male").hasClass('active'))
                 gender='female'

                var details={name: $("#name").val(),email: $("#email").val(),password:$("#password").val(),age:$("#age").val(),occupation:this.selectedOccupation,gender:gender};
                console.log(details);
                $.ajax({
                    type: "POST",
                    url: "http://localhost/writewell_production/public/signup/index",
                    data: JSON.stringify(details),
                    success: function(result){
                        if($.parseJSON(result).status==1)
                        {
                            shared.router.navigate('projects',true);
                        }

                    },
                    error: function(error){alert('error');}
                });



		},
		toggleActive: function (e) {
			$('.button-gender').removeClass('active');
			$(e.currentTarget).addClass('active');
		},
		setTemplate: function (e) {
			this.selectedOccupation = $(e.currentTarget).attr('data-id');
			console.log('occupation: ' + this.selectedOccupation);
			$('#templatePlaceholder').html($(e.currentTarget).html());
		}
	});
	return mainHomeView;
});