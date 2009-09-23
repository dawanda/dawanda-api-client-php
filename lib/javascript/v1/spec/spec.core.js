describe "DaWanda.API"
  describe 'getUserDetails("meko")'
    before
      result = apiResponses.getUserDetails
      console.log(this.currentSuite.name)
      console.log(result)
    end

    it 'should not fail'
      result.failure.should.be null
      result.success.should.not.be null
    end
    
    it "should have found one user"
      result.success.entries.should.be 1
      result.success.pages.should.be 1
      result.success.type.should.be "user"
      result.success.result.user.should.not.be null
    end
    
    it 'should have found the user meko'
      result.success.result.user.name.should.be "meko"
    end
  end
  
  describe 'getUserDetails("foobar")'
    before
      result = apiResponses.getFailingUserDetails
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should fail because of no matching user'
      result.success.should.be null
      result.failure.should.not.be null
    end
    
    it 'should result in an error'
      result.failure.error.should.not.be null
      result.failure.error.message.should.be "The User foobar could not be found"
      result.failure.error.status.should.be 404
    end
  end
  
  describe 'searchUser("me")'
    before
      result = apiResponses.searchUsers
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should return an array of users'
      typeof(result.success.result.users).should.be "object"
      result.success.result.users.length.should.not.be null
    end
    
    it 'should have the type "user"'
      result.success.type.should.be "user"
    end
  end
  
  describe "searchUsers('m') + pagination"
    before
      result = apiResponses.searchUsersWithPagination
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should have more than one page'
      result.success.pages.should.be_greater_than 1
      parseInt(result.success.params.per_page).should.be 1
      parseInt(result.success.params.page).should.be 2
    end
  end
  
  describe 'searchShops("me")'
    before
      result = apiResponses.searchShops
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find some shops'
      result.success.type.should.be "shop"
      result.success.result.shops.should.not.be null
    end
    
    it 'should find shops with me'
      result.success.result.shops.each(function(shop) {
        shop.name.gsub(" ", "").toLowerCase().indexOf("me").should.be_greater_than -1
      })
    end
  end
  
  describe 'getUserPinboards("meko")'
    before
      result = apiResponses.getUserPinboards
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find some pinboards'
      result.success.result.pinboards.should.not.be null
      result.success.type.should.be "pinboard"
    end
    it 'should really be pinboards'
      result.success.result.pinboards.each(function(pinboard) {
        pinboard.restful_path.indexOf("pinboard").should.be_greater_than -1
      });
    end
  end
  
  describe 'getShopDetails("meko")'
    before
      result = apiResponses.getShopDetails
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find mekos shop'
      result.success.result.shop.name.should.be "MEKO STORE"
    end
  end
  
  describe 'getProductsForShop("meko")'
    before
      result = apiResponses.getProductsForShop
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find products'
      result.success.type.should.be "product"
      result.success.result.products.each(function(product) {
        product.restful_path.indexOf("product").should.be_greater_than -1
      });
    end
    
    it 'should find only mekos products'
      result.success.result.products.each(function(product) {
        product.user.name.should.be "meko"
      });
    end
  end
  
  describe 'getProductsForCategory'
    before
      result = apiResponses.getProductsForCategory
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find products'
      result.success.type.should.be "product"
      result.success.result.products.each(function(product) {
        product.restful_path.indexOf("product").should.be_greater_than -1
      });
    end
    
    it 'should only find products in category 107'
      result.success.type.should.be "product"
      result.success.result.products.each(function(product) {
        product.category.id.should.be 107
      });
    end
  end
  
  describe 'getProductDetails'
    before
      result = apiResponses.getProductDetails
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find a product'
      result.success.type.should.be "product"
    end
    
    it 'should find the product 325606'
      result.success.result.product.id.should.be 325606
    end
  end
  
  describe 'getCategoriesForShop'
    before
      result = apiResponses.getCategoriesForShop
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should return shop categories'
      result.success.type.should.be "shop_category"
      result.success.result.shop_categories.each(function(shop_category) {
        shop_category.restful_path.indexOf("shop_categories").should.be_greater_than -1
      });
    end
  end
  
  describe 'getShopCategoryDetails'
    before
      result = apiResponses.getShopCategoryDetails
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find shop category 5002'
      result.success.type.should.be "shop_category"
      result.success.result.shop_category.id.should.be 5002
    end
  end
  
  describe 'getProductsForShopCategory'
    before
      result = apiResponses.getProductsForShopCategory
      console.log(this.currentSuite.name)
      console.log(result)
    end

    it 'should find products from meko'
      result.success.result.products.each(function(product) {
        product.user.name.should.be "meko"
      })
    end
  end
  
  describe 'getTopCategories'
    before
      result = apiResponses.getTopCategories
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should return categories'
      result.success.type.should.be "category"
      result.success.result.categories.each(function(category) {
        category.restful_path.indexOf("categories").should.be_greater_than -1
      })
    end
  end
  
  describe 'getCategoryDetails'
    before
      result = apiResponses.getCategoryDetails
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should return a category'
      result.success.type.should.be "category"
      result.success.result.category.id.should.be 70
    end
  end
  
  describe 'getChildrenOfCategory'
    before
      result = apiResponses.getChildrenOfCategory
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should return categories'
      result.success.type.should.be "category"
      result.success.result.categories.each(function(category) {
        category.restful_path.indexOf("categories").should.be_greater_than -1
      })
    end
    
    it 'should only find child categories of 70'
      result.success.result.categories.each(function(category) {
        category.parent_id.should.be 70
      });
    end
  end
  
  describe 'getColors'
    before
      result = apiResponses.getColors
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should find colors'
      result.success.type.should.be "image_color"
    end
    
    it 'should find more than 10 colors (ignore per_page default)'
      result.success.result.image_colors.size().should.be_greater_than 10
    end
  end
  
  describe 'getProductsForColor'
    before
      result = apiResponses.getProductsForColor
      console.log(this.currentSuite.name)
      console.log(result)
    end
    
    it 'should return products'
      result.success.type.should.be "product"
    end
    
    it 'should only find white products'
      result.success.result.products.each(function(product) {
        var hasWhiteColor = false
        product.image_colors.each(function(color) {
          if(color.hex == "ffffff") hasWhiteColor = true;
        })
        hasWhiteColor.should.be true
      });
    end
  end
end