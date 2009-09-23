api = new DaWanda.API("4462d4e18202291359ff68fca0a93db179455c23", "de")
apiResponses = {}

function requestParamsHash(hash_key, params) {
  apiResponses[hash_key] = { success: null, failure: null}
  
  return {
    onSuccess: function(data) { apiResponses[hash_key].success = data },
    onFailure: function(data) { apiResponses[hash_key].failure = data },
    params: params
  }
}

api.getUserDetails("meko", requestParamsHash("getUserDetails"))
api.getUserDetails("foobar", requestParamsHash("getFailingUserDetails"))
api.searchUsers("me", requestParamsHash("searchUsers"))
api.searchUsers("m", requestParamsHash("searchUsersWithPagination", {per_page: 1, page: 2}));

api.getShopDetails("meko", requestParamsHash("getShopDetails"));
api.searchShops("me", requestParamsHash("searchShops"));

api.getUserPinboards("meko", requestParamsHash("getUserPinboards"));

api.getProductsForCategory(107, requestParamsHash("getProductsForCategory"));
api.getProductsForShopCategory(5002, requestParamsHash("getProductsForShopCategory"));
api.getProductsForShop("meko", requestParamsHash("getProductsForShop"));
api.getProductDetails(325606, requestParamsHash("getProductDetails"));
api.getProductsForColor("ffffff", requestParamsHash("getProductsForColor"));
api.searchProductForColor("ffffff", "a", requestParamsHash("searchProductForColor"));

api.getCategoriesForShop("meko", requestParamsHash("getCategoriesForShop"))
api.getShopCategoryDetails(5002, requestParamsHash("getShopCategoryDetails"));

api.getTopCategories(requestParamsHash("getTopCategories"));
api.getCategoryDetails(70, requestParamsHash("getCategoryDetails"));
api.getChildrenOfCategory(70, requestParamsHash("getChildrenOfCategory"));

api.getColors(requestParamsHash("getColors"));