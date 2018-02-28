var ModalWindow = function (options) {
    "use strict";
	this.contentLoaded = false;
    this.rootDiv = $('<div/>');

	if (options.CallBack === undefined || options.CallBack === null) {
        this.callBack = function () {};
    } else {
        this.callBack = options.CallBack;
    }

	if (options.Disposed === undefined || options.Disposed === null) {
        this.disposed = function () {};
    } else {
        this.disposed = options.Disposed;
    }

    if (options.Title === undefined || options.Title === null) {
        throw "Title must be defined";
    }
    this.title = options.Title;

    if (options.Message === undefined || options.Message === null || options.length < 1) {
		if(options.Url === undefined || options.Url === null) {
			throw "You must specify a message or a URL.";
		} else {
			this.LoadContent(options.Url);
		}
    }
    this.message = options.Message;

    if (options.Buttons === undefined || options.Buttons === null) {
        this.buttons = [ModalWindow.OkButton];
    } else {
        this.buttons = options.Buttons;
    }

    if (options.ExtraData === undefined || options.ExtraData === null) {
        this.ExtraData = null;
    } else {
        this.ExtraData = options.ExtraData;
    }

    if (options.AllowClickAway === undefined || options.AllowClickAway === null) {
        this.clickAway = true;
    } else {
        this.clickAway = false;
    }

    if (options.XOffSet !== undefined || options.XOffSet !== null) {
        if (Number(options.XOffSet) !== 'NaN') {
            this.rootDiv.attr('style', 'left:' + options.XOffSet);
        } else {
            throw "Xoffset must be a number";
        }
    }

    if (options.YOffSet !== undefined || options.YOffSet !== null) {
        if (Number(options.YOffSet) !== 'NaN') {
            this.rootDiv.attr('style', 'top:' + options.YOffSet);
        } else {
            throw "Yoffset must be a number";
        }
    }

    if (options.Center !== undefined && options.Center === true) {
        this.rootDiv.attr('style', 'top: 25%;');
    }

    if (options.BackDrop === undefined || options.BackDrop === null) {
        this.backdrop = true;
    } else {
        this.backdrop = options.BackDrop;
    }

    if (options.AllowKeyboardEsc === undefined || options.AllowKeyboardEsc === null) {
        this.keyboard = true;
    } else {
        this.keyboard = options.AllowKeyboardEsc;
    }

    if (options.ShowCloseButton === undefined) {
        this.showClose = true;
    } else {
        this.showClose = options.ShowCloseButton;
    }

	if (options.PostInit === undefined || options.PostInit === null) {
		this.PostInit = function () {};
	} else {
		this.PostInit = options.PostInit;
	}

	this.options = options;
};

ModalWindow.prototype.DisplayWindow = function(){
	this.GenerateHTML();
	var uniqid = String.fromCharCode(65 + Math.floor(Math.random() * 26)) + Date.now(),
		options = {};

	this.rootDiv.attr('id', uniqid);
	$('body').append(this.rootDiv);
	if (!this.clickAway) {
		options.backdrop = 'static';
	}
	if (!this.backdrop) {
		options.backdrop = this.backdrop;
	}
	if (this.keyboard) {
		options.keyboard = true;
	} else {
		options.keyboard = false;
	}
	options.show = true;
	this.rootDiv.modal(options);
	$(this.rootDiv).toggleClass('hide');
	this.PostInit(this.rootDiv);
}; //end DisplayWindow

ModalWindow.prototype.LoadContent = function(url){
	var content = "";
	var self = this;

	$.ajax({
		method: "GET",
		url: url,
		cache: false,
		data: "text/html"
	}).done(function (result) {
		self.message = result;
		self.DisplayWindow();
	})
	.fail(function (result) {
		alert("There was an error loading the data: " + result);
	});
}; //end LoadContent

ModalWindow.prototype.Show = function () {
    "use strict";
	if(this.options.Url === undefined){
		this.DisplayWindow();
	} //end if
};

ModalWindow.prototype.Finished = function (eventData) {
    "use strict";
    var self = eventData.data.self;

	var form = self.rootDiv.find('form');
	var formData = null;

	if(form !== undefined && form !== null){
		formData = {};
		formData["FormUrl"] = form.attr("action");

		form.serializeArray().map(function(item) {
			formData[item.name] = item.value;
		});
	}

	var event = jQuery.Event( "click" );
	self.callBack($(this).attr('data-result'), event, formData, self.ExtraData, self.rootDiv);

	if (!event.isDefaultPrevented()) {
		self.rootDiv.remove();
		$('.modal-backdrop').remove();
		$('body').toggleClass('modal-open');
		self.disposed();
	}
};

ModalWindow.prototype.GenerateHTML = function () {
    "use strict";
    var container = $('<div/>'),
		header = $('<div/>'),
        headerText = $('<h4/>'),
        body = $('<div/>'),
        footer = $('<div/>'),
        closeButton = $('<button/>'),
        wrapper = $('<div/>'),
		button,
        i;

	this.rootDiv.attr('class', 'modal hide fade');
	container.attr('class','modal-content');
    header.attr('class', 'modal-header');
	headerText.attr('class', 'modal-title');

    if (this.showClose) {
        closeButton.attr('class', 'close');
        closeButton.attr('type', 'button');
        closeButton.attr('data-dismiss', 'modal');
        closeButton.attr('aria-hidden', 'true');
        closeButton.attr('data-result', 'closed');
        closeButton.bind('click', {
            self: this
        }, this.Finished);
        closeButton.html('&times;');
        header.append(closeButton);
    }

    headerText.html(this.title);
    header.append(headerText);
    body.attr('class', 'modal-body');
    body.html(this.message);

    footer.attr('class', 'modal-footer');
    for (i = 0; i < this.buttons.length; i = i + 1) {
        button = $('<a/>');
        button.attr('class', 'btn ' + this.buttons[i][0]);
        button.text(this.buttons[i][1]);
        button.attr('data-result', this.buttons[i][2]);
        button.bind('click', {
            self: this
        }, this.Finished);
        footer.append(button);
        button = null;
    }

    container.append(header);
    container.append(body);
    container.append(footer);

	wrapper.attr('class', 'modal-dialog');
	wrapper.append(container);
	this.rootDiv.append(wrapper);
};

ModalWindow.OkButton = ['btn-primary', 'Ok', 'ok'];
ModalWindow.YesButton = ['btn-success', 'Yes', 'true'];
ModalWindow.YesDangerButton = ['btn-danger', 'Yes', 'true'];
ModalWindow.NoButton = ['btn-primary', 'No', 'false'];
ModalWindow.NoDangerButton = ['btn-danger', 'No', 'false'];
