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
    var endpoints = {
      "searchUsers":                "/users/search.js",
      "searchShops":                "/shops/search.js",
      "searchProducts":             "/products/search.js",
      "searchProductForColor":      "/colors/#{id}/products/search.js",
      "getUserDetails":             "/users/#{id}.js",
      "getUserPinboards":           "/users/#{id}/pinboards.js",
      "getShopDetails":             "/shops/#{id}.js",
      "getProductsForShop":         "/shops/#{id}/products.js",
      "getCategoriesForShop":       "/shops/#{id}/shop_categories.js",
      "getShopCategoryDetails":     "/shop_categories/#{id}.js",
      "getProductsForShopCategory": "/shop_categories/#{id}/products.js",
      "getTopCategories":           "/categories/top.js",
      "getCategoryDetails":         "/categories/#{id}.js",
      "getChildrenOfCategory":      "/categories/#{id}/children.js",
      "getProductsForCategory":     "/categories/#{id}/products.js",
      "getProductDetails":          "/products/#{id}.js",
      "getColors":                  "/colors.js",
      "getProductsForColor":        "/colors/#{id}/products.js"
    };
    
    var template = new Template("#{host}/api/v#{version}" + endpoints[callee]);    
    var replacements = $H({ host: this.host, version: this.API_VERSION, id: id });
    return template.evaluate(replacements.toObject());
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