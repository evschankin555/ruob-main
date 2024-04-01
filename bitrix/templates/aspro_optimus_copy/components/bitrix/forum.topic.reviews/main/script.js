;(function(window){
	window.FTRList = function (params) {
		this.id = 'FTRList' + params.form.id;
		this.mess = {};
		this.form = params.form;
		if (!!params["id"]) {
			for (var ii = 0; ii < params["id"].length; ii++) {
				this.bind(params["id"][ii]);
			}
		}
		this.params = {
			preorder: (params.preorder == "Y"),
			pageNumber: params.pageNumber,
			pageCount: params.pageCount
		};
		BX.addCustomEvent(this.form, 'onAdd', BX.delegate(this.add, this));
		BX.addCustomEvent(this.form, 'onRequest', BX.delegate(function () {
			if (typeof this.params.pageNumber != 'undefined') {
				var pageNumberInput = BX.findChild(this.form, {attr: {name: 'pageNumber'}});
				if (!pageNumberInput) {
					pageNumberInput = BX.create("input", {props: {type: "hidden", name: 'pageNumber'}});
					this.form.appendChild(pageNumberInput);
				}
				pageNumberInput.value = this.params.pageNumber;
			}
			if (typeof this.params.pageCount != 'undefined') {
				var pageCountInput = BX.findChild(this.form, {attr: {name: 'pageCount'}});
				if (!pageCountInput) {
					pageCountInput = BX.create("input", {props: {type: "hidden", name: 'pageCount'}});
					this.form.appendChild(pageCountInput);
				}
				pageCountInput.value = this.params.pageCount;
			}
		}, this));
		BX.addCustomEvent(this.form, 'onResponse', BX.delegate(function () {
			var input_pageno = BX.findChild(this.form, { 'attr': { 'name': 'pageNumber' }}, true);
			if (input_pageno) {
				BX.remove(input_pageno);
			}
		}, this));
	};
	window.FTRList.prototype = {
		add : function(id, result)
		{
			var
				container = BX(this.form.id + 'container'),
				listform,
				post = {className: /reviews-reply-form|reviews-collapse/},
				msgNode = window.fTextToNode(result.message);
			if (! container)
			{
				container = BX.create('div', {
					'attrs' : {
						'id' : this.form.id + 'container'},
					'props': {
						'className': 'reviews-block-container reviews-reviews-block-container'},
					'children': [
						BX.create('div', {
							'props': {
								'className': 'reviews-block-outer'
							},
							'children': [
								BX.create('div', {
									'props': {
										'className': 'reviews-block-inner'
									}
								})
							]
						})
					]
				});
				window.fReplaceOrInsertNode(container, null, BX.findChild(document, post, true).parentNode, post);
				container = BX(this.form.id + 'container');
			}
			listform = (container ? BX.findChild(container, {className: 'reviews-block-inner'}, true) : null);
			if (msgNode && listform)
			{
				if (!!result["allMessages"])
				{
					window.fReplaceOrInsertNode(msgNode, listform, BX.findChild(document, post, true).parentNode, post);

					if (!!result.navigation && !!result.pageNumber)
					{
						var navDIV = window.fTextToNode(result.navigation), i,
							navPlaceholders = (navDIV ? BX.findChildren(container.parentNode, { className : 'reviews-navigation-box' } , true) : null);
						if (navDIV)
						{
							if (!navPlaceholders) // then add ...
							{
								container.parentNode.insertBefore(BX.create('div', {props:{className:"reviews-navigation-box reviews-navigation-top"}}), container);
								var tmpDiv = container;
								// bottom
								do {
									tmpDiv = tmpDiv.nextSibling;
								} while (tmpDiv && tmpDiv.nodeType != 1);
								var bottomPager = BX.create('div', {props:{className:"reviews-navigation-box reviews-navigation-bottom"}});
								if (tmpDiv)
									container.parentNode.insertBefore( bottomPager , tmpDiv);
								else
									container.parentNode.appendChild(bottomPager);

								navPlaceholders = BX.findChildren(container.parentNode, { className : 'reviews-navigation-box' } , true);
							}
							for (i = 0; i < navPlaceholders.length; i++)
								navPlaceholders[i].innerHTML = navDIV.innerHTML;
						}

						this.params.pageNumber = result.pageNumber;
						this.params.pageCount = result.pageCount;
					}
					if (result["messagesID"] && typeof result["messagesID"] == "object")
					{
						for (var ii = 0; ii < result["messagesID"].length; ii++)
						{
							if (result["messagesID"][ii] != id)
								this.bind(result["messagesID"][ii]);
						}
					}
				}
				else if (typeof result.message != 'undefined')
				{
					if (this.params.preorder)
						listform.appendChild(msgNode);
					else
						listform.insertBefore(msgNode, listform.firstChild);
				}
				window.fRunScripts(result.message);
				this.bind(id);
			}
		},
		bind : function(id)
		{
			var node = BX('message' + id);
			if (!!node)
			{
				this.mess['m' + id] = {
					node : node,
					author : {
						id : node.getAttribute("bx-author-id"),
						name : node.getAttribute("bx-author-name")
					}
				};

				var buttons = BX.findChildren(node, {tagName : "A", className : "reviews-button-small"}, true),
					func = BX.delegate(function() { var res = BX.proxy_context; this.act(res.getAttribute("bx-act"), id); }, this),
					func2 = BX.delegate(function(){ this.act('reply', id); }, this),
					func3 = BX.delegate(function(){ this.act('quote', id); }, this);
				if (!!buttons && buttons.length > 0)
				{
					for (var ii = 0; ii < buttons.length; ii++)
					{
						if (buttons[ii].getAttribute("bx-act") == "moderate" || buttons[ii].getAttribute("bx-act") == "del")
						{
							BX.adjust(buttons[ii],
								{
									events : { click : func },
									attrs : {
										"bx-href" : buttons[ii].getAttribute("href"),
										href : "javascript:void(0);"
									}
								}
							);
						}
						else if (!!this.form)
						{
							if (buttons[ii].getAttribute("bx-act") == "reply")
							{
								BX.bind(buttons[ii], 'click', func2);
							}
							else if (buttons[ii].getAttribute("bx-act") == "quote")
							{
								BX.bind(buttons[ii], 'mousedown', func3);
							}
						}
					}
				}
			}
		},
		act : function(act, id)
		{
			if (!id || !this.mess['m' + id]) {
				BX.DoNothing();
			}
			else if (act == 'quote') {
				var selection = window.GetSelection();
				if (document["getSelection"])
				{
					selection = selection.replace(/\r\n\r\n/gi, "_newstringhere_").replace(/\r\n/gi, " ");
					selection = selection.replace(/  /gi, "").replace(/_newstringhere_/gi, "\r\n\r\n");
				}

				if (selection === "" && id > 0 && BX('message_text_' + id, true))
				{
					var message = BX('message_text_' + id, true);
					if (typeof(message) == "object" && message)
						selection = message.innerHTML;
				}

				selection = selection.replace(/[\n|\r]*<br(\s)*(\/)*>/gi, "\n");

				// Video
				var videoWMV = function(str, p1)
				{
					var result = ' ';
					var rWmv = /showWMVPlayer.*?bx_wmv_player.*?file:[\s'"]*([^"']*).*?width:[\s'"]*([^"']*).*?height:[\s'"]*([^'"]*).*?/gi;
					var res = rWmv.exec(p1);
					if (res)
						result = "[VIDEO WIDTH="+res[2]+" HEIGHT="+res[3]+"]"+res[1]+"[/VIDEO]";
					if (result == ' ')
					{
						var rFlv = /bxPlayerOnload[\s\S]*?[\s'"]*file[\s'"]*:[\s'"]*([^"']*)[\s\S]*?[\s'"]*height[\s'"]*:[\s'"]*([^"']*)[\s\S]*?[\s'"]*width[\s'"]*:[\s'"]*([^"']*)/gi;
						res = rFlv.exec(p1);
						if (res)
							result = "[VIDEO WIDTH="+res[3]+" HEIGHT="+res[2]+"]"+res[1]+"[/VIDEO]";
					}
					return result;
				}

				selection = selection.replace(/<script[^>]*>/gi, '\001').replace(/<\/script[^>]*>/gi, '\002');
				selection = selection.replace(/\001([^\002]*)\002/gi, videoWMV)
				selection = selection.replace(/<noscript[^>]*>/gi, '\003').replace(/<\/noscript[^>]*>/gi, '\004');
				selection = selection.replace(/\003([^\004]*)\004/gi, " ");

				// Quote & Code & Table
				selection = selection.replace(/<table class\=[\"]*forum-quote[\"]*>[^<]*<thead>[^<]*<tr>[^<]*<th>([^<]+)<\/th><\/tr><\/thead>[^<]*<tbody>[^<]*<tr>[^<]*<td>/gi, "\001");
				selection = selection.replace(/<table class\=[\"]*forum-code[\"]*>[^<]*<thead>[^<]*<tr>[^<]*<th>([^<]+)<\/th><\/tr><\/thead>[^<]*<tbody>[^<]*<tr>[^<]*<td>/gi, "\002");
				selection = selection.replace(/<table class\=[\"]*data-table[\"]*>[^<]*<tbody>/gi, "\004");
				selection = selection.replace(/<\/td>[^<]*<\/tr>(<\/tbody>)*<\/table>/gi, "\003");
				selection = selection.replace(/[\r|\n]{2,}([\001|\002])/gi, "\n$1");

				var ii = 0;
				while(ii++ < 50 && (selection.search(/\002([^\002\003]*)\003/gi) >= 0 || selection.search(/\001([^\001\003]*)\003/gi) >= 0))
				{
					selection = selection.replace(/\002([^\002\003]*)\003/gi, "[CODE]$1[/CODE]").replace(/\001([^\001\003]*)\003/gi, "[QUOTE]$1[/QUOTE]");
				}

				var regexReplaceTableTag = function(s, tag, replacement)
				{
					var re_match = new RegExp("\004([^\004\003]*)("+tag+")([^\004\003]*)\003", "i");
					var re_replace = new RegExp("((?:\004)(?:[^\004\003]*))("+tag+")((?:[^\004\003]*)(?:\003))", "i");
					var ij = 0;
					while((ij++ < 300) && (s.search(re_match) >= 0))
						s = s.replace(re_replace, "$1"+replacement+"$3");
					return s;
				}

				ii = 0;
				while(ii++ < 10 && (selection.search(/\004([^\004\003]*)\003/gi) >= 0))
				{
					selection = regexReplaceTableTag(selection, "<tr>", "[TR]");
					selection = regexReplaceTableTag(selection, "<\/tr>", "[/TR]");
					selection = regexReplaceTableTag(selection, "<td>", "[TD]");
					selection = regexReplaceTableTag(selection, "<\/td>", "[/TD]");
					selection = selection.replace(/\004([^\004\003]*)\003/gi, "[TABLE]$1[/TD][/TR][/TABLE]");
				}

				// Smiles
				if (BX.browser.IsIE())
					selection = selection.replace(/<img(?:(?:\s+alt\s*=\s*\"?smile([^\"\s]+)\"?)|(?:\s+\w+\s*=\s*[^\s>]*))*>/gi, "$1");
				else
					selection = selection.replace(/<img.*?alt=[\"]*smile([^\"\s]+)[\"]*[^>]*>/gi, "$1");

				// Hrefs
				selection = selection.replace(/<a[^>]+href=[\"]([^\"]+)\"[^>]+>([^<]+)<\/a>/gi, "[URL=$1]$2[/URL]");
				selection = selection.replace(/<a[^>]+href=[\']([^\']+)\'[^>]+>([^<]+)<\/a>/gi, "[URL=$1]$2[/URL]");
				selection = selection.replace(/<[^>]+>/gi, " ").replace(/&lt;/gi, "<").replace(/&gt;/gi, ">").replace(/&quot;/gi, "\"");

				selection = selection.replace(/(smile(?=[:;8]))/g, "");

				selection = selection.replace(/\&shy;/gi, "");
				selection = selection.replace(/\&nbsp;/gi, " ");
				BX.onCustomEvent(this.form, 'onQuote', [{author : this.mess['m' + id]["author"], id : id, text : selection}]);
			}
			else if (act == 'reply') {
				BX.onCustomEvent(this.form, 'onReply', [{author : this.mess['m' + id]["author"], id : id}]);
			}
			else if (act == 'del' && (!confirm(BX.message('f_cdm')))) {
				BX.DoNothing();
			}
			else if (act == 'moderate' || act == 'del') {
				var
					link = BX.proxy_context,
					href = link.getAttribute("bx-href").replace(/.AJAX_CALL=Y/g,'').replace(/.sessid=[^&]*/g, ''),
					tbl = BX.findParent(link, {'tag' : 'table'}),
					note = BX.create('a', {attrs: { className : 'reply-action-note'}}),
					replyActionDone = function() {
						BX.remove(note);
						BX.show(link.parentNode);
					};

				BX.hide(link.parentNode);
				note.innerHTML = BX.message('f_wait');
				link.parentNode.parentNode.appendChild(note);
				BX.ajax.loadJSON(href,
					{AJAX_CALL : "Y", sessid : BX.bitrix_sessid()},
					BX.delegate(function(res) {
						if (res.status && !!tbl) {
							BX.onCustomEvent(window, 'onForumCommentAJAXAction', [act]);
							if (act == 'del') {
								var curpage = window["curpage"] || top.window.location.href;
								BX.fx.hide(tbl, 'scroll', {time: 0.15, callback_complete: BX.delegate(function() {
									BX.remove(tbl);
									replyActionDone();
									var reviews = BX.findChild(BX(this.form.id + 'container'), {'class': 'reviews-post-table'}, true, true);
									if ((!reviews) || (reviews.length < 1))
										if (this.params.pageNumber > 1)
											BX.reload(curpage);
								}, this)});
							} else {
								var bHidden = BX.hasClass(tbl, 'reviews-post-hidden');
								var label = (bHidden ? BX.message('f_hide') : BX.message('f_show'));
								var tbldiv = BX.findChild(tbl, { className : 'reviews-text'}, true);
								BX.fx.hide(tbldiv, 'fade', {time: 0.1, callback_complete: function() {
									BX.toggleClass(tbl, 'reviews-post-hidden');
									link.innerHTML = label;
									href = href.replace(new RegExp('REVIEW_ACTION='+(bHidden ? 'SHOW' : 'HIDE')), ('REVIEW_ACTION='+(bHidden ? 'HIDE' : 'SHOW')));
									link.setAttribute('bx-href', href);
									BX.fx.show(tbldiv, 'fade', {time: 0.1});
									replyActionDone();
									BX.style(tbldiv, 'background-color', (bHidden ? '#FFFFFF' : '#E5F8E3')); // IE9
								}});
							}
						} else {
							BX.addClass(note, 'error');
							note.innerHTML = '<span class="errortext">'+res.message+'</span>';
						}
					}, this)
				);
			}
			return false;
		}
	};

	window.FTRForm = function(params) {
		this.id = 'FTRForm' + params.form.id;
		this.form = params.form;
		this.editorName = params.editorName;
		this.editorId = params.editorId;
		this.editor = window[params.editorName];
		this.windowEvents = {};
		if (!this.editor)
		{
			this.windowEvents.LHE_OnInit = BX.delegate(function(pEditor) { if (pEditor.id == this.editorId) { this.addParsers(pEditor); this.editor = pEditor; } }, this);
			this.windowEvents.LHE_ConstructorInited = BX.delegate(function(pEditor) { if (pEditor.id == this.editorId) { if (!this.editor) { this.addParsers(pEditor); this.editor = pEditor; } this.editor.ucInited = true; } }, this);
			BX.addCustomEvent(window, 'LHE_OnInit', this.windowEvents.LHE_OnInit);
			BX.addCustomEvent(window, 'LHE_ConstructorInited', this.windowEvents.LHE_ConstructorInited);
		} else {
			this.addParsers(this.editor);
		}
		this.params = {
			messageMax : 64000
		};

		BX.addCustomEvent(this.form, 'onQuote', BX.delegate(function(params){this.show(); this.paste(params, 'QUOTE');}, this));
		BX.addCustomEvent(this.form, 'onReply', BX.delegate(function(params){this.show(); this.paste(params, 'REPLY');}, this));
	};
	window.FTRForm.prototype = {
		addParsers : function(pEditor)
		{
			pEditor.AddParser(
				{
					name: 'postuser',
					obj: {
						Parse: function(sName, sContent, pLEditor)
						{
							sContent = sContent.replace(/\[USER\s*=\s*(\d+)\]((?:\s|\S)*?)\[\/USER\]/ig, function(str, id, name)
							{
								id = parseInt(id);
								name = BX.util.trim(name);

								return '<span id="' + pLEditor.SetBxTag(false, {tag: "postuser", params: {value : id}}) +
									'" style="color: #2067B0; border-bottom: 1px dashed #2067B0;">' + name + '</span>';
							});
							return sContent;
						},
						UnParse: function(bxTag, pNode, pLEditor)
						{
							if (bxTag.tag == 'postuser')
							{
								var name = '';
								for (var i = 0; i < pNode.arNodes.length; i++)
									name += pLEditor._RecursiveGetHTML(pNode.arNodes[i]);
								name = BX.util.trim(name);
								return "[USER=" + bxTag.params.value + "]" + name +"[/USER]";
							}
							return "";
						}
					}
				}
			);
		},
		validate : function(ajax_post)
		{
			var form = this.form,
				errors = "",
				MessageLength = form.REVIEW_TEXT.value.length;
			if (form['BXFormSubmit_save'])
				return true; // ValidateForm may be run by BX.submit one more time

			if (form.TITLE && (form.TITLE.value.length < 2))
				errors += BX.message('no_topic_name');

			if (MessageLength < 2)
				errors += BX.message('no_message');
			else if ((this.params.messageMax !== 0) && (MessageLength > this.params.messageMax))
				errors += BX.message('max_len').replace(/\#MAX_LENGTH\#/gi, this.params.messageMax).replace(/\#LENGTH\#/gi, MessageLength);

			if (errors !== "")
			{
				alert(errors);
				return false;
			}

			var btnSubmit = BX.findChild(form, {'attribute':{'name':'send_button'}}, true);
			if (btnSubmit) { btnSubmit.disabled = true; }
			var btnPreview = BX.findChild(form, {'attribute':{'name':'view_button'}}, true);
			if (btnPreview) { btnPreview.disabled = true; }

			if (ajax_post == 'Y')
			{
				BX.onCustomEvent(window, 'onBeforeForumCommentAJAXPost', [form]);
				BX.onCustomEvent(this.form, 'onRequest', [this, ajax_post]);

				setTimeout(BX.delegate(function(){
					if(!window.renderRecaptchaById || !window.asproRecaptcha || !window.asproRecaptcha.key)
					{
						BX.ajax.submit(form, BX.delegate(this.get, this));
						return true;
					}
					if(window.asproRecaptcha.params.recaptchaSize == 'invisible' && typeof grecaptcha != 'undefined')
					{
						if($(form).find('.g-recaptcha-response').val())
						{
							BX.ajax.submit(form, BX.delegate(this.get, this));
						}
						else
						{
							grecaptcha.execute($(form).find('.g-recaptcha').data('widgetid'));
						}
					}
					else
					{
						BX.ajax.submit(form, BX.delegate(this.get, this));
					}
					
					// BX.ajax.submit(form, BX.delegate(this.get, this));
				}, this), 50);
				return false;
			}
			return true;
		},
		get : function()
		{
			this.form['BXFormSubmit_save'] = null;
			var result = window.forumAjaxPostTmp;
			window["curpage"] = window["curpage"] || top.window.location.href;

			BX.onCustomEvent(window, 'onForumCommentAJAXPost', [result, this.form]);

			if (typeof result == 'undefined' || result.reload)
			{
				BX.reload(window["curpage"]);
				return;
			}

			if (result["status"])
			{
				if (!!result["allMessages"] || typeof result["message"] != 'undefined')
				{
					BX.onCustomEvent(this.form, 'onAdd', [result["messageID"], result]);
					this.clear();
				}
				else if (!!result["previewMessage"])
				{
					var previewDIV = BX.findChild(document, {'className': 'reviews-preview'}, true),
						previewParent = BX.findChild(document, {className : /reviews-reply-form|reviews-collapse/}, true).parentNode,
						previewNode = window.fTextToNode(result["previewMessage"]);
					window.fReplaceOrInsertNode(previewNode, previewDIV, previewParent, {'className' : /reviews-reply-form|reviews-collapse/});

					window.PostFormAjaxStatus('');
					window.fRunScripts(result["previewMessage"]);
				}
				var message = (!!result["messageID"] ? BX('message'+result["messageID"]) : null);
				if (message) {
					BX.scrollToNode(message);
				}
			}

			var arr = this.form.getElementsByTagName("input");
			for (var i=0; i < arr.length; i++)
			{
				var butt = arr[i];
				if (butt.getAttribute("type") == "submit")
					butt.disabled = false;
			}

			if (result["statusMessage"])
				window.PostFormAjaxStatus(result["statusMessage"]);

			BX.onCustomEvent(this.form, 'onResponse', [result, this.form]);
			BX.onCustomEvent(window, 'onAfterForumCommentAJAXPost', [result, this.form]);
		},
		clear : function()
		{
			this.editor.ReInit('');

			if (this.editor.fAutosave)
				BX.bind(this.editor.pEditorDocument, 'keydown',
					BX.proxy(this.editor.fAutosave.Init, this.editor.fAutosave));
			if (!BX.type.isDomNode(this.form)) return;
			var previewDIV = BX.findChild(document, {'className' : 'reviews-preview'}, true);
			if (previewDIV)
				BX.remove(previewDIV);

			var i = 0, fileDIV, fileINPUT, fileINPUT1;
			while ((fileDIV = BX('upload_files_'+(i++)+'_' + this.form.index.value)) && !!fileDIV)
			{
				if ((fileINPUT = BX.findChild(fileDIV, {'tag':'input'})) && !!fileINPUT1)
				{
					fileINPUT1 = BX.clone(fileINPUT);
					fileINPUT1.value = '';
					fileINPUT.parentNode.insertBefore(fileINPUT1, fileINPUT);
					fileINPUT.parentNode.removeChild(fileINPUT);
				}
				BX.hide(fileDIV);
			}
			var attachLink = BX.findChild(this.form, {'className':"forum-upload-file-attach"}, true);
			if (attachLink)
				BX.show(attachLink);
			var attachNote = BX.findChild(this.form, {'className':"reviews-upload-info"}, true);
			if (attachNote)
				BX.hide(attachNote);

			var captchaIMAGE = null,
				captchaHIDDEN = BX.findChild(this.form, {attr : {'name': 'captcha_code'}}, true),
				captchaINPUT = BX.findChild(this.form, {attr: {'name':'captcha_word'}}, true),
				captchaDIV = BX.findChild(this.form, {'className':'reviews-reply-field-captcha-image'}, true);
			if (captchaDIV)
				captchaIMAGE = BX.findChild(captchaDIV, {'tag':'img'});
			if (captchaHIDDEN && captchaINPUT && captchaIMAGE)
			{
				captchaINPUT.value = '';
				BX.ajax.getCaptcha(function(result) {
					captchaHIDDEN.value = result["captcha_sid"];
					captchaIMAGE.src = '/bitrix/tools/captcha.php?captcha_code='+result["captcha_sid"];
				});
			}
		},
		show : function()
		{
			BX.onCustomEvent(this.form, 'onBeforeShow', [this]);
			BX.show(this.form.parentNode);
			BX.scrollToNode(BX.findChild(this.form, {'attribute': { 'name' : 'send_button' }}, true));
			setTimeout(BX.delegate(function() {
				this.editor.SetFocus();
				BX.defer(this.editor.SetFocus, this.editor)();
			}, this), 100);
			BX.onCustomEvent(this.form, 'onAfterShow', [this]);
			return false;
		},
		hide : function()
		{
			BX.onCustomEvent(this.form, 'onBeforeHide', [this]);
			BX.hide(this.form.parentNode);
			BX.onCustomEvent(this.form, 'onAfterHide', [this]);
			return false;
		},
		transverse : function()
		{
			if (this.form.parentNode.style.display == 'none')
				this.show();
			else
				this.hide();
			return false;
		},
		paste : function(params, tag)
		{
			BX.onCustomEvent(this.form, 'onPaste', [params, tag, this]);
			var author = (!!params["author"] ? params["author"] : ''),
				text = (!!params["text"] ? params["text"] : ''),
				id = (!!params["id"] ? params["id"] : '');

			if (!author)
				author = '';
			else if (this.editor.sEditorMode == 'code' && this.editor.bBBCode) { // BB Codes
				if (author.id > 0)
					author = "[USER=" + author.id + "]" + author.name + "[/USER]";
				else
					author = author.name;
			} else if (this.editor.sEditorMode == 'html') { // WYSIWYG
				author.name = author.name.replace(/</gi, '&lt;').replace(/>/gi, '&gt;');
				if (author.id > 0)
					author = '<span id="' + this.editor.SetBxTag(false, {'tag': "postuser", 'params': {'value' : author.id}}) +
						'" style="color: #2067B0; border-bottom: 1px dashed #2067B0;">' + author.name + '</span>';
				else
					author = '<span>' + author.name + '</span>';
			}

			if (tag == "QUOTE")
			{
				if (this.editor.sEditorMode == 'code' && this.editor.bBBCode) { // BB Codes
					this.editor.WrapWith("[QUOTE" + (id > 0 ? (" ID=" + id) : "") + "]", "[/QUOTE]",
						(author !== '' ? ( author + BX.message("f_author") + "\n") : '') + text);
				} else if (this.editor.sEditorMode == 'html') { // WYSIWYG
					this.editor.InsertHTML('<blockquote class="bx-quote" id="mess' + id + '">' +
						(author !== '' ? ( author + BX.message("f_author") + '<br/>') : '') +
						this.editor.ParseContent(text, true) + "</blockquote><br/>");
				}
			}
			else
			{
				text = (!!author ? (author + ', ') : "") + this.editor.ParseContent(text, true);
				if (this.editor.sEditorMode == 'code' && this.editor.bBBCode) { // BB Codes
					this.editor.WrapWith("", "", text);
				} else if (this.editor.sEditorMode == 'html') { // WYSIWYG
					this.editor.InsertHTML(text);
				}
			}
			this.editor.SetFocus();
			BX.defer(this.editor.SetFocus, this.editor)();
		}
	};
	BX.ready(function() {
		if (BX.browser.IsIE())
		{
			var posts = BX.findChildren(document, {'className':'reviews-post-table'}, true), ii, all, i;
			if (!posts) return;
			for (ii = 0; ii < posts.length; ii++) {
				all = posts[ii].getElementsByTagName('*');
				i = all.length;
				while (i--) {
					if (all[i].scrollWidth > all[i].offsetWidth) {
						all[i].style['paddingBottom'] = '20px';
						all[i].style['overflowY'] = 'hidden';
					}
				}
			}
		}
	});

	window.fTextToNode = function(text)
	{
		var tmpdiv = BX.create('div');
		tmpdiv.innerHTML = text;
		if (tmpdiv.childNodes.length > 0)
			return tmpdiv;
		else
			return null;
	};

	window.PostFormAjaxStatus = function(status)
	{
		var arNote = BX.findChild(document, { className : 'reviews-note-box'} , true, true), i;
		if (arNote)
			for (i = 0; i <= arNote.length; i++)
				BX.remove(arNote[i]);

		var msgBox = BX.findChild(document, { className : 'reviews-block-container' } , true);
		if (!msgBox) return;

		if (status.length < 1) return;

		var statusDIV = window.fTextToNode(status);
		if (!statusDIV) return;

		var beforeDivs = ['reviews-reply-form', 'reviews-collapse'];
		var tmp = msgBox;
		while ((tmp = tmp.nextSibling) && !!tmp)
		{
			if (tmp.nodeType == 1)
			{
				var insert = false;
				for (i = 0; i < beforeDivs.length; i++)
				{
					if (BX.hasClass(tmp, beforeDivs[i]))
					{
						insert = true;
						break;
					}
				}
				if (insert)
				{
					tmp.parentNode.insertBefore(statusDIV, tmp);
					break;
				}
			}
		}
	};

	window.SetReviewsAjaxPostTmp = function(text)
	{
		window.forumAjaxPostTmp = text;
	};

	window.fReplaceOrInsertNode = function(sourceNode, targetNode, parentTargetNode, beforeTargetNode)
	{
		var nextNode = null;

		if (!BX.type.isDomNode(parentTargetNode)) return false;

		if (!BX.type.isDomNode(sourceNode) && !BX.type.isArray(sourceNode) && sourceNode.length > 0)
			if (! (sourceNode = window.fTextToNode(sourceNode))) return false;

		if (BX.type.isDomNode(targetNode)) // replace
		{
			parentTargetNode = targetNode.parentNode;
			nextNode = targetNode.nextSibling;
			parentTargetNode.removeChild(targetNode);
		}

		if (!nextNode)
			nextNode = BX.findChild(parentTargetNode, beforeTargetNode, true);

		if (nextNode)
		{
			nextNode.parentNode.insertBefore(sourceNode, nextNode);
		} else {
			parentTargetNode.appendChild(sourceNode);
		}

		return true;
	};

	window.fRunScripts = function(msg)
	{
		var ob = BX.processHTML(msg, true);
		BX.ajax.processScripts(ob.SCRIPT, true);
	};

	window.ShowLastEditReason = function(checked, div)
	{
		if (div)
		{
			if (checked)
				div.style.display = 'block';
			else
				div.style.display = 'none';
		}
	};

	window.AttachFile = function(iNumber, iCount, sIndex, oObj)
	{
		var element = null;
		var bFined = false;
		iNumber = parseInt(iNumber);
		iCount = parseInt(iCount);

		document.getElementById('upload_files_info_' + sIndex).style.display = 'block';
		for (var ii = iNumber; ii < (iNumber + iCount); ii++)
		{
			element = document.getElementById('upload_files_' + ii + '_' + sIndex);
			if (!element || typeof(element) === null)
				break;
			if (element.style.display == 'none')
			{
				bFined = true;
				element.style.display = 'block';
				break;
			}
		}
		var bHide = (!bFined ? true : (ii >= (iNumber + iCount - 1)));
		if (bHide === true)
			oObj.style.display = 'none';
	};

	/**
	 * @return {string}
	 */
	window.GetSelection = function()
	{
		var range, text = '';
		if (window.getSelection) {
			range = window.getSelection();
			text = range.toString();
		} else if (document.selection) {
			range = document.selection;
			text = range.createRange().text;
		}
		return text;
	}
})(window);
