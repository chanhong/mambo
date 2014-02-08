var FCKXml=function()
{}
function escapeHTML(text){text=text.replace('\n','');text=text.replace('&','&amp;');text=text.replace('<','&lt;');text=text.replace('>','&gt;');return text;}
FCKXml.prototype.GetHttpRequest=function()
{if(window.XMLHttpRequest)
return new XMLHttpRequest();else if(window.ActiveXObject)
return new ActiveXObject("MsXml2.XmlHttp");}
FCKXml.prototype.LoadUrl=function(urlToCall,asyncFunctionPointer)
{var oFCKXml=this;var bAsync=(typeof(asyncFunctionPointer)=='function');var oXmlHttp=this.GetHttpRequest();oXmlHttp.open("GET",urlToCall,bAsync);if(bAsync)
{oXmlHttp.onreadystatechange=function()
{if(oXmlHttp.readyState==4)
{oFCKXml.DOMDocument=oXmlHttp.responseXML;asyncFunctionPointer(oFCKXml);}}}
oXmlHttp.send(null);if(!bAsync)
this.DOMDocument=oXmlHttp.responseXML;}
FCKXml.prototype.SelectNodes=function(xpath)
{if(document.all)
return this.DOMDocument.selectNodes(xpath);else
{var aNodeArray=new Array();var xPathResult=this.DOMDocument.evaluate(xpath,this.DOMDocument,this.DOMDocument.createNSResolver(this.DOMDocument.documentElement),XPathResult.ORDERED_NODE_ITERATOR_TYPE,null);if(xPathResult)
{var oNode=xPathResult.iterateNext();while(oNode)
{aNodeArray[aNodeArray.length]=oNode;oNode=xPathResult.iterateNext();}}
return aNodeArray;}}
FCKXml.prototype.SelectSingleNode=function(xpath)
{if(document.all)
return this.DOMDocument.selectSingleNode(xpath);else
{var xPathResult=this.DOMDocument.evaluate(xpath,this.DOMDocument,this.DOMDocument.createNSResolver(this.DOMDocument.documentElement),9,null);if(xPathResult&&xPathResult.singleNodeValue)
return xPathResult.singleNodeValue;else
return null;}}