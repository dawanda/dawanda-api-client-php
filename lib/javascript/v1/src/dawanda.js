DaWanda = Class.create();
DaWanda.API = Class.create();
DaWanda.API.User = Class.create();


DaWanda.API.prototype = {
  apiKey: null,
  host: null,
  API_VERSION: 1,
  CALLBACK_PARAM_NAME: "callback",
  AVAILABLE_LANGUAGES: ["de", "fr", "en"],
  cache: {},
  
  initialize: function(apiKey, language) {
    // check for dependencies
    if((typeof Prototype=='undefined') || (typeof Element == 'undefined') || (typeof Element.Methods=='undefined') || (typeof Ajax.JSONRequest == 'undefined'))
       throw("This frameworks requires the Prototype JavaScript framework and it's jsonp extension.");
    
    if(!apiKey)
      throw("Initialization failed due to missing api key! Please pass a key as first param.");
      
    if(!language)
      throw("Initialization failed due to unspecified language! Please pass the language as second param.");
    
    if(this.AVAILABLE_LANGUAGES.indexOf(language) == -1)
      throw("DaWanda only supports the following languages: "+this.AVAILABLE_LANGUAGES.join(", "));
    
    this.apiKey = apiKey;
    this.host = "http://" + language + ".dawanda.com";
  },
  
  searchUsers: function(keyword, options) {
    var url = this.getApiUrl("searchUsers");
    options = this.addKeywordToOptions(keyword, options);
    this.requestApi(url, options);
  },
  
  searchShops: function(keyword, options) {
    var url = this.getApiUrl("searchShops");
    options = this.addKeywordToOptions(keyword, options);
    this.requestApi(url, options);
  },
  
  searchProducts: function(keyword, options) {
    var url = this.getApiUrl("searchProducts");
    options = this.addKeywordToOptions(keyword, options);
    this.requestApi(url, options);
  },
  
  getUserDetails: function(id, options) {
    var url = this.getApiUrl("getUserDetails", id);
    this.requestApi(url, options);
  },
  
  getUserPinboards: function(id, options) {
    var url = this.getApiUrl("getUserPinboards", id);
    this.requestApi(url, options);
  },
  
  getShopDetails: function(id, options) {
    var url = this.getApiUrl("getShopDetails", id);
    this.requestApi(url, options);
  },
  
  getProductsForShop: function(id, options) {
    var url = this.getApiUrl("getProductsForShop", id);
    this.requestApi(url, options);
  },
  
  getCategoriesForShop: function(id, options) {
    var url = this.getApiUrl("getCategoriesForShop", id);
    this.requestApi(url, options);
  },
  
  getShopCategoryDetails: function(id, options) {
    var url = this.getApiUrl("getShopCategoryDetails", id);
    this.requestApi(url, options);
  },
  
  getProductsForShopCategory: function(id, options) {
    var url = this.getApiUrl("getProductsForShopCategory", id);
    this.requestApi(url, options);
  },
  
  getTopCategories: function(options) {
    var url = this.getApiUrl("getTopCategories");
    this.requestApi(url, options);
  },
  
  getCategoryDetails: function(id, options) {
    var url = this.getApiUrl("getCategoryDetails", id);
    this.requestApi(url, options);
  },
  
  getChildrenOfCategory: function(id, options) {
    var url = this.getApiUrl("getChildrenOfCategory", id);
    this.requestApi(url, options);
  },
  
  getProductsForCategory: function(id, options) {
    var url = this.getApiUrl("getProductsForCategory", id);
    this.requestApi(url, options);
  },
  
  getProductDetails: function(id, options) {
    var url = this.getApiUrl("getProductDetails", id);
    this.requestApi(url, options);
  },
  
  getColors: function(options) {
    var url = this.getApiUrl("getColors");
    this.requestApi(url, options);
  },
  
  getProductsForColor: function(id, options) {
    var url = this.getApiUrl("getProductsForColor", id);
    this.requestApi(url, options);
  },
  
  searchProductForColor: function(id, keyword, options) {
    var url = this.getApiUrl("searchProductForColor", id);
    options = this.addKeywordToOptions(keyword, options);
    this.requestApi(url, options);
  },
    
  getApiUrl: function(callee, id) {
    var indexTemplate   = new Template("#{host}/api/v#{version}/#{parent}.js");
    var showTemplate    = new Template("#{host}/api/v#{version}/#{parent}/#{id}.js");
    var scopedTemplate  = new Template("#{host}/api/v#{version}/#{parent}/#{id}/#{method}.js");
    var customTemplate  = new Template("#{host}/api/v#{version}/#{parent}/#{method}.js");
    
    var replacements = $H({ host: this.host, version: this.API_VERSION, id: id });
    var usedTemplate;

    switch(callee) {
      case "searchUsers":
        replacements = replacements.merge({parent: "users", method: "search"});
        usedTemplate = customTemplate;
        break;
      case "searchShops":
        replacements = replacements.merge({parent: "shops", method: "search"});
        usedTemplate = customTemplate;
        break;
      case "searchProducts":
        replacements = replacements.merge({parent: "products", method: "search"});
        usedTemplate = customTemplate;
        break;
      case "searchProductForColor":
        replacements = replacements.merge({parent: "colors", method: "products/search"});
        usedTemplate = scopedTemplate;
        break;
      case "getUserDetails":
        replacements = replacements.merge({parent: "users"});
        usedTemplate = showTemplate;
        break;
      case "getUserPinboards":
        replacements = replacements.merge({parent: "users", method: "pinboards"});
        usedTemplate = scopedTemplate;
        break;
      case "getShopDetails":
        replacements = replacements.merge({parent: "shops"});
        usedTemplate = showTemplate;
        break;
      case "getProductsForShop":
        replacements = replacements.merge({parent: "shops", method: "products"});
        usedTemplate = scopedTemplate;
        break;
      case "getCategoriesForShop":
        replacements = replacements.merge({parent: "shops", method: "shop_categories"});
        usedTemplate = scopedTemplate;
        break;
      case "getShopCategoryDetails":
        replacements = replacements.merge({parent: "shop_categories"});
        usedTemplate = showTemplate;
        break;
      case "getProductsForShopCategory":
        replacements = replacements.merge({parent: "shop_categories", method: "products"});
        usedTemplate = scopedTemplate;
        break;
      case "getTopCategories":
        replacements = replacements.merge({ parent: "categories", method: "top"});
        usedTemplate = customTemplate;
        break;
      case "getCategoryDetails":
        replacements = replacements.merge({ parent: "categories"});
        usedTemplate = showTemplate;
        break;
      case "getChildrenOfCategory":
        replacements = replacements.merge({ parent: "categories", method: "children"});
        usedTemplate = scopedTemplate;
        break;
      case "getProductsForCategory":
        replacements = replacements.merge({ parent: "categories", method: "products"});
        usedTemplate = scopedTemplate;
        break;
      case "getProductDetails":
        replacements = replacements.merge({ parent: "products"});
        usedTemplate = showTemplate;
        break;
      case "getColors":
        replacements = replacements.merge({ parent: "colors"});
        usedTemplate = indexTemplate;
        break;
      case "getProductsForColor":
        replacements = replacements.merge({ parent: "colors", method: "products"});
        usedTemplate = scopedTemplate;
        break;
    }
    
    return usedTemplate.evaluate(replacements.toObject());
  },
  
  requestApi: function(url, options) {
    options.params = ($H(options.params).merge({api_key: this.apiKey})).toObject();
    var cacheKey = url + "?" + Object.toQueryString(options.params);
    var _this = this;
    
    if(this.cache[cacheKey]) {
      var response = this.cache[cacheKey];
      
      if(response.responseJSON.error) {
        if(options.onFailure) options.onFailure(response.responseJSON);
      } else {
        if(options.onSuccess) options.onSuccess(response.responseJSON.response);
      }
    } else {
      new Ajax.JSONRequest(url, {
        timeout: 30,
        callbackParamName: this.CALLBACK_PARAM_NAME,
        parameters: options.params,
        onSuccess: function(response) {
          _this.cache[cacheKey] = response;
          if(response.responseJSON.error) {
            if(options.onFailure) options.onFailure(response.responseJSON);
          } else {
            if(options.onSuccess) options.onSuccess(response.responseJSON.response);
          }
        },
        onFailure: function(response) {
          _this.cache[cacheKey] = response;
          if(options.onFailure) options.onFailure(response);
        }
      });
    }
  },
  
  addKeywordToOptions: function(keyword, options) {
    if(options && options.params)
      options.params = ($H(options.params).merge({ keyword: keyword })).toObject();
    else
      options = ($H(options).merge({ params: { keyword: keyword } })).toObject();

    return options;
  }
};