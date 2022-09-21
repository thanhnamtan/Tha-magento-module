define(['jquery', 'uiComponent', 'ko', 'mage/url'], function ($, Component, ko, urlBuilder) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Tha_Trans/Trans/tablelist'
        },
        initialize: function (config, node) {
            this._super();
            this.items = ko.observableArray(config.datas);
            this.local_config = ko.observableArray(config.local_config);

            console.log(config);
            this.message = ko.observable("");
            this.page_size = ko.observable(2);
            this.local_config_selected = ko.observable();
            this.current_page = ko.observable(1);
            this.page_size_arr = [2, 4, 5, 8, 10, 25];
            this.text_data = ko.observable("text data");
            this.message_type = ko.observable("black");
            
            this.form_data = ko.observable({
                created_at: "",
                entity_id: "",
                local_area: "",
                trans_key: "",
                trans_value: ""
            });
            this.trans_key = ko.computed(function(){return this.form_data().trans_key;}.bind(this));
            this.trans_value = ko.computed(function(){return this.form_data().trans_value;}.bind(this));
            this.local_area = ko.computed(function(){return this.form_data().local_area;}.bind(this));
            this.entity_id = ko.computed(function(){return this.form_data().entity_id;}.bind(this));

            this.page_arr = ko.observable(this.items().length);
            this.page_number = ko.observable(Math.floor(this.items().length/2));
           
            this.all_inde = ko.computed(function () {
                var indexes = [], i;
                for(i = 0; i < Math.ceil(this.items().length/(this.page_size() ?? 2)); i++){
                    indexes.push(i + 1);
                }   
                return indexes;
            }.bind(this));
    
            this.current_item = ko.computed(function(){
                if (this.current_page != 'defined') {
                    let curen = this.items().slice((this.current_page()-1)*(this.page_size() ?? 2), (this.current_page()-1)*(this.page_size() ?? 2) + (this.page_size() ?? 2));
                    return curen;
                }
            }.bind(this));

            // $(document).ajaxSuccess(function (event, xhr, settings) {
            //     if (settings.url.indexOf("trasnpost") !== -1 && typeof(xhr.responseText)) {
            //         let new_data = JSON.parse(JSON.parse(xhr.responseText));
            //         this.datas.push(new_data)
            //     }
            // }.bind(this));

            setInterval(function(){if (this.message) {this.message("");}}.bind(this), 3500);
        },

		xoa : function(_idd){
			let _items = this.items();
			let index = _items.findIndex(item => item.entity_id == _idd.entity_id);
			if (index != -1) {
                $.ajax({
                    url: urlBuilder.build("trasnpost?isAjax=true"),
                    data: JSON.stringify(_idd),
                    type: 'DELETE',
                    dataType: 'json',
                    beforeSend: function(){}.bind(this),
                    success: function(data, status, xhr){
                        if (_.isEqual(_idd.entity_id, JSON.parse(data).entity_id)) {
                            this.showMessage("Deleted the item success!", "green");
                            let log = _items.splice(index, 1);
                            this.items(_items);
                        }
                        if (this.form_data().entity_id == _idd.entity_id) {
                            this.form_data({
                                created_at: "",
                                entity_id: "",
                                local_area: "",
                                trans_key: "",
                                trans_value: ""
                            });
                        }
                    }.bind(this),
                    error: function(xhr, status, errorThrown){
                        this.showMessage("Can not delete the value!","red");
                        // console.log(xhr, status, errorThrown);
                    }.bind(this)
                });
			}else{
				console.log("not has index");
			}
		},

		add : function(){
			let be_text = $("#be_trasn").val();
			let af_text = $("#af_trasn").val();
            let local_area = $("#local_area").val();

            if (be_text && af_text && local_area) {
                $.ajax({
                    url: urlBuilder.build("trasnpost?isAjax=true"),
                    data: {
                        be_trasn: be_text,
                        af_trasn: af_text,
                        local_area: local_area
                    },
                    type: 'POST',
                    dataType: 'json',
                    beforeSend: function() {
                        // show some loading icon
                        console.log("begin send the data");
                    }.bind(this),
                    success: function(data, status, xhr) {
                        // data contains your controller response'
                        console.log(data);
                        this.showMessage("Added data suscess!", "green");
                        $("#be_trasn").val("");
                        $("#af_trasn").val("");
                        $("#local_area").val(null);

                        // this.form_data({  // khoong chajy
                        //     created_at: JSON.parse(data).created_at,
                        //     entity_id: "",
                        //     local_area: "",
                        //     trans_key: "",
                        //     trans_value: ""
                        // });
                        this.items.push(JSON.parse(data));
                    }.bind(this),
                    error: function(xhr, status, errorThrown) {
                        console.log(errorThrown);
                        this.showMessage("Can not add the data!", "red");
                    }.bind(this)
                });
            }else{
                this.showMessage("The data can not null!!!", "red");
            }
		},

		change_page : function(page){
			console.log(page);
			this.current_page(page);
		},

		setOptionDisable : function(){
		 console.log(this);
         this.showMessage("Message for log!", "blue");
		},

        update: function (item) {
            this.form_data(item);
        },

        updatePust: function(){
            let be_text = $("#be_trasn").val();
			let af_text = $("#af_trasn").val();
            let local_area = $("#local_area").val();
            if (this.entity_id && be_text && af_text && local_area) {
                $.ajax({
                    url: urlBuilder.build("trasnpost?isAjax=true"),
                    data: JSON.stringify({
                        be_trasn: be_text,
                        af_trasn: af_text,
                        local_area: local_area,
                        entity_id: this.entity_id()
                    }),
                    type: 'PUT',
                    dataType: 'json',
                    beforeSend: function() {}.bind(this),
                    success: function(data, status, xhr) {
                        data = JSON.parse(data);
                        let _items = this.items();
                        let index = _items.findIndex(item => item.entity_id == data.entity_id);
                        this.showMessage("Updated data success!", "green");
                        if (index != -1) {
                            _items[index] = data;
                            this.items(_items);
                            this.form_data({
                                created_at: "",
                                entity_id: "",
                                local_area: "",
                                trans_key: "",
                                trans_value: ""
                            });
                        }else{
                            this.showMessage("Has error in process, please try again!", "red");
                        }
                    }.bind(this),
                    error: function(xhr, status, errorThrown) {
                        // console.log(errorThrown);
                        this.showMessage("Can not update the data!", "red");
                    }.bind(this)
                });
            }else{

            }
        },

        showMessage: function(message, type = "black"){
            this.message(message);
            this.message_type(type);
        }

    });
}
);