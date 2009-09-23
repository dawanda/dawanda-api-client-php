api = new DaWanda.API("380d7924396f5596116f3d8815c97dfd8c975582", "de")
apiResponses = {}

function requestParamsHash(hash_key, params) {
  apiResponses[hash_key] = { success: null, failure: null}
  
  return {
    onSuccess: function(data) { apiResponses[hash_key].success = data },
    onFailure: function(data) { apiResponses[hash_key].failure = data },
    params: params
  }
}

api.getUserDetails("dawanda-merchandise", requestParamsHash("getUserDetails"))
api.getUserDetails("foobar", requestParamsHash("getFailingUserDetails"))
api.searchUsers("me", requestParamsHash("searchUsers"))
api.searchUsers("m", requestParamsHash("searchUsersWithPagination", {per_page: 1, page: 2}));

api.getShopDetails("dawanda-merchandise", requestParamsHash("getShopDetails"));
api.searchShops("mek", requestParamsHash("searchShops"));

api.getUserPinboards("dawanda-merchandise", requestParamsHash("getUserPinboards"));

api.getProductsForCategory(610, requestParamsHash("getProductsForCategory"));
api.getProductsForShopCategory(22934, requestParamsHash("getProductsForShopCategory"));
api.getProductsForShop("dawanda-merchandise", requestParamsHash("getProductsForShop"));
api.getProductDetails(325606, requestParamsHash("getProductDetails"));
api.getProductsForColor("ffffff", requestParamsHash("getProductsForColor"));
api.searchProductForColor("ffffff", "a", requestParamsHash("searchProductForColor"));

api.getCategoriesForShop("dawanda-merchandise", requestParamsHash("getCategoriesForShop"))
api.getShopCategoryDetails(22934, requestParamsHash("getShopCategoryDetails"));

api.getTopCategories(requestParamsHash("getTopCategories"));
api.getCategoryDetails(318, requestParamsHash("getCategoryDetails"));
api.getChildrenOfCategory(318, requestParamsHash("getChildrenOfCategory"));

api.getColors(requestParamsHash("getColors"));