var varienTabsExtended = new Class.create();

varienTabsExtended.prototype = Object.extend(new varienTabs(),{
    initialize : function(containerId, destElementId,  activeTabId, shadowTabs){
        this.containerId    = containerId;
        this.destElementId  = destElementId;
        this.activeTab = null;
        this.tabs = new Array();
        this.tabOnClick     = this.tabMouseClick.bindAsEventListener(this);

        var tabs = $$('#'+this.containerId+' li a.tab-item-link');

        for (var tab=0; tab<tabs.length; tab++) {
            if(tabs[tab].id.indexOf(this.containerId) !== -1){
                this.tabs.push(tabs[tab]);
            }
        }
        this.hideAllTabsContent();
        for (var tab=0; tab<this.tabs.length; tab++) {
            Event.observe(this.tabs[tab],'click',this.tabOnClick);
            // move tab contents to destination element
            if($(this.destElementId)){
                var tabContentElement = $(this.getTabContentElementId(this.tabs[tab]));
                if(tabContentElement && tabContentElement.parentNode.id != this.destElementId){
                    $(this.destElementId).appendChild(tabContentElement);
                    tabContentElement.container = this;
                    tabContentElement.statusBar = this.tabs[tab];
                    tabContentElement.tabObject  = this.tabs[tab];
                    this.tabs[tab].contentMoved = true;
                    this.tabs[tab].container = this;
                    this.tabs[tab].show = function(){
                        this.container.showTabContent(this);
                    }
                    if(varienGlobalEvents){
                        varienGlobalEvents.fireEvent('moveTab', {tab:this.tabs[tab]});
                    }
                }
            }
/*
            // this code is pretty slow in IE, so lets do it in tabs*.phtml
            // mark ajax tabs as not loaded
            if (Element.hasClassName($(this.tabs[tab].id), 'ajax')) {
                Element.addClassName($(this.tabs[tab].id), 'notloaded');
            }
*/
            // bind shadow tabs
            if (this.tabs[tab].id && shadowTabs && shadowTabs[this.tabs[tab].id]) {
                this.tabs[tab].shadowTabs = shadowTabs[this.tabs[tab].id];
            }
        }

        this.displayFirst = activeTabId;

        if($(document).loaded){
            this.moveTabContentInDest();
        }else{
            Event.observe(window,'load',this.moveTabContentInDest.bind(this));
        }
    }
});
varienTabs = varienTabsExtended;