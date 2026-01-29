library(DescTools)

View(SENIC)
str(SENIC)

#=========================================== 1 ===========================================#


#### DATA PARTITIONING ####

indexsetframe = sample(2,nrow(SENIC),replace = T, prob = c(0.80,0.20))
trainsenic  = SENIC[indexsetframe==1,]
testsenic = SENIC[indexsetframe==2,]


#=========================================== 2A ===========================================#


#### CORRELATIONAL MATRIX ####

corr_matrix <- cor(SENIC,method="spearman")

# bed - census
# bed - nurses
# bed - facilities
# census - nurses
# census - facilities
# facilities - nurses

trainsenic$region = as.factor(trainsenic$region)
testsenic$region = as.factor(testsenic$region)


#### VARIABLE SELECTION ####

#============================ LOG ============================#

## Intercept Model
log_glmnull = glm(stay~1,family = gaussian(link = "log"),data = trainsenic)
summary(log_glmnull)

## Full model
log_glmfull = glm(stay~.,family = gaussian(link = "log"),data = trainsenic)
summary(log_glmfull)



#### GLM SATURATED (LOG)

## 4 variables
log_glmsat4a = glm(stay ~ age+inf+region+census, 
                   family = gaussian(link = "log"), data = trainsenic)
summary(log_glmsat4a)


log_glmsat4b = glm(stay ~ age+inf+region+nurses, 
                   family = gaussian(link = "log"), data = trainsenic)
summary(log_glmsat4b)

## 3 variables

log_glmsat3a = glm(stay ~ inf+region+census, 
                  family = gaussian(link = "log"), data = trainsenic)
summary(log_glmsat3a)

log_glmsat3b = glm(stay ~ age+region+census, 
                  family = gaussian(link = "log"), data = trainsenic)
summary(log_glmsat3b)

#### PROPOSED GLM (LOG) with 2 variables

log_glmprop = glm(stay ~ region+census, 
                  family = gaussian(link = "log"), data = trainsenic)
summary(log_glmprop)

###### LRT TEST (LOG) ######

anova(log_glmprop, log_glmsat4a, test = "Chisq") ## LOWEST
anova(log_glmprop, log_glmsat4b, test = "Chisq")
anova(log_glmprop, log_glmsat3a, test = "Chisq")
anova(log_glmprop, log_glmsat3b, test = "Chisq")

### LRT TEST INTERPRETATION (LOG) ###

a = 0.05 
p-value of log_glmsat4a (LOWEST)  = 2.416e-08
Therefore, we REJECT the null hypothesis. There is sufficient evidence to state that
the saturated model(log_glmsat4a) with variables (age, inf, region, census) contributes well 
to model fitness or the prediction of depedent variable (stay)




#============================ IDENTITY ============================#

## Intercept Model
identity_glmnull = glm(stay~1,family = gaussian(link = "identity"),data = trainsenic)
summary(identity_glmnull)

## Full model
identity_glmfull = glm(stay~.,family = gaussian(link = "identity"),data = trainsenic)
summary(identity_glmfull)


#### GLM SATURATED (IDENTITY)

## 4 variables
identity_glmsat4a = glm(stay ~ age+inf+region+census, 
                   family = gaussian(link = "identity"), data = trainsenic)
summary(identity_glmsat4a)


identity_glmsat4b = glm(stay ~ age+inf+region+nurses, 
                   family = gaussian(link = "identity"), data = trainsenic)
summary(identity_glmsat4b)

## 3 variables

identity_glmsat3a = glm(stay ~ inf+region+census, 
                   family = gaussian(link = "identity"), data = trainsenic)
summary(identity_glmsat3a)

identity_glmsat3b = glm(stay ~ age+region+census, 
                   family = gaussian(link = "identity"), data = trainsenic)
summary(identity_glmsat3b)

#### PROPOSED GLM (IDENTITY) with 2 variables

identity_glmprop = glm(stay ~ region+census, 
                  family = gaussian(link = "identity"), data = trainsenic)
summary(identity_glmprop)

###### LRT TEST (IDENTITY) ######

anova(identity_glmprop, identity_glmsat4a, test = "Chisq") ## LOWEST
anova(identity_glmprop, identity_glmsat4b, test = "Chisq")
anova(identity_glmprop, identity_glmsat3a, test = "Chisq")
anova(identity_glmprop, identity_glmsat3b, test = "Chisq")


### LRT TEST INTERPRETATION (IDENTITY) ###

a = 0.05 
p-value = 2.939e-05

Therefore, we REJECT the null hypothesis. There is sufficient evidence to state that
the saturated model(identity_glmsat4a) with variables (age, inf, region, census) contributes well 
to model fitness or the prediction of depedent variable (stay)




#============================ INVERSE ============================#

## Intercept Model
inverse_glmnull = glm(stay~1,family = gaussian(link = "inverse"),data = trainsenic)
summary(inverse_glmnull)

## Full model
inverse_glmfull = glm(stay~.,family = gaussian(link = "inverse"),data = trainsenic)
summary(inverse_glmfull)


#### GLM SATURATED (IDENTITY)

## 4 variables
inverse_glmsat4a = glm(stay ~ age+inf+region+census, 
                        family = gaussian(link = "inverse"), data = trainsenic)
summary(inverse_glmsat4a)


inverse_glmsat4b = glm(stay ~ age+inf+region+nurses, 
                        family = gaussian(link = "inverse"), data = trainsenic)
summary(inverse_glmsat4b)

## 3 variables

inverse_glmsat3a = glm(stay ~ inf+region+census, 
                        family = gaussian(link = "inverse"), data = trainsenic)
summary(inverse_glmsat3a)

inverse_glmsat3b = glm(stay ~ age+region+census, 
                        family = gaussian(link = "inverse"), data = trainsenic)
summary(inverse_glmsat3b)

#### PROPOSED GLM (INVERSE) with 2 variables

inverse_glmprop = glm(stay ~ region+census, 
                       family = gaussian(link = "inverse"), data = trainsenic)
summary(inverse_glmprop)

###### LRT TEST (INVERSE) ######

anova(inverse_glmprop, inverse_glmsat4a, test = "Chisq") ## LOWEST
anova(inverse_glmprop, inverse_glmsat4b, test = "Chisq")
anova(inverse_glmprop, inverse_glmsat3a, test = "Chisq")
anova(inverse_glmprop, inverse_glmsat3b, test = "Chisq")


### LRT TEST INTERPRETATION (INVERSE) ###

a = 0.05 
p-value = 2.902e-07

Therefore, we REJECT the null hypothesis. There is sufficient evidence to state that
the saturated model(inverse_glmsat4a) with variables (age, inf, region, census) contributes well 
to model fitness or the prediction of depedent variable (stay)



#=========================================== 2B ===========================================#


#### PREDICTION METRICS ##### 

glmlog = glm(stay ~ age+inf+region+census, 
                   family = gaussian(link = "log"), data = trainsenic)

glmMLR = glm(stay ~ age+inf+region+census, 
             family = gaussian(link = "identity"), data = trainsenic)
summary(glmMLR)
glminverse = glm(stay ~ age+inf+region+census, 
                 family = gaussian(link = "inverse"), data = trainsenic)


AIC(glmlog,glmMLR,glminverse)

For the three models, glmlog, glmMLR and glminverse,
glminverse has a lower AIC than glmlog and glmMLR, 
glminverse is considered the better model in terms of the trade-off 
between goodness of fit and complexity.


#### Predictive Capability of LOG ####

predict_log = predict(glmlog, newdata = testsenic)

MAE_log = mean(abs(testsenic$stay-predict_log))

MSE_log = mean((testsenic$stay-predict_log)^2)

RMSE_log = sqrt(MSE_log)

MAPE_log = mean(abs(testsenic$stay-predict_log)/testsenic$stay)

log_list <- list(c("MSE"=MSE_log,"RMSE"=RMSE_log,"MAE"=MAE_log,"MAPE"=MAPE_log))

#=====INTERPRETATION====#

the MAE value of 7.841 indicates that, on average, the absolute difference
between the predictions and actual values of the dependent variable ’stay’ 
is approximately 7.841 units.
The MSE value of 67.36 represents the average squared error between predicted and actual values. 
The RMSE value of 8.207, which is the square root of MSE, suggests that, on average, 
the predictions are approximately 8.207 units close to the actual values.
The MAPE of the GLM model is 76 percent which suggests that the model has a substantial prediction 
error, as it tends to deviate from the actual target values by 76% on average.

#### Predictive Capability of IDENTITY ####

predict_MLR = predict(glmMLR, newdata = testsenic)

MAE_MLR = mean(abs(testsenic$stay-predict_MLR))

MSE_MLR = mean((testsenic$stay-predict_MLR)^2)

RMSE_MLR = sqrt(MSE_MLR)

MAPE_MLR = mean(abs(testsenic$stay-predict_MLR)/testsenic$stay)

mlr_list <- list(c("MSE"=MSE_MLR,"RMSE"=RMSE_MLR,"MAE"=MAE_MLR,"MAPE"=MAPE_MLR))

#=====INTERPRETATION====#

the MAE value of 1.133 indicates that, on average, the absolute difference
between the predictions and actual values of the dependent variable ’stay’ 
is approximately 1.133 units.
The MSE value of 3.541 represents the average squared error between predicted and actual values. 
The RMSE value of 1.88, which is the square root of MSE, suggests that, on average, the 
predictions are approximately 1.88 units close to the actual values.
The MAPE of the GLM model is 10 percent which suggests that the model has a substantial prediction 
error, as it tends to deviate from the actual target values by 10% on average.


#### Predictive Capability of INVERSE ####

predict_inverse = predict(glminverse, newdata = testsenic)

MAE_inverse = mean(abs(testsenic$stay-predict_inverse))

MSE_inverse = mean((testsenic$stay-predict_inverse)^2)

RMSE_inverse = sqrt(MSE_inverse)

MAPE_inverse = mean(abs(testsenic$stay-predict_inverse)/testsenic$stay)

inverse_list <- list(c("MSE"=MSE_inverse,"RMSE"=RMSE_inverse,"MAE"=MAE_inverse,"MAPE"=MAPE_inverse))

#=====INTERPRETATION====#

The MAE value of 10.01 indicates that, on average, the absolute difference
between the predictions and actual values of the dependent variable ’stay’ 
is approximately 10.01 units.
The MSE value of 106.61 represents the average squared error between predicted and actual values. 
The RMSE value of 10.33, which is the square root of MSE, suggests that, on average, the 
predictions are approximately 10.33 units close to the actual values.
The MAPE of the GLM model is 99 percent which suggests that the model has a substantial prediction 
error, as it tends to deviate from the actual target values by 99% on average.

####### PREDICTIVE CAPABILITY COMPARISON #######

Based on the three predictive measures, it would seem that the MLR model would be the better model 
in terms of the predictive capability since it produced significantly lower values.



#### PSEUDO R-SQUARED ####

PseudoR2(glmlog, which = c("McFadden","CoxSnell","Nagelkerke","Efron"))
PseudoR2(glmMLR, which = c("McFadden","CoxSnell","Nagelkerke","Efron"))
PseudoR2(glminverse, which = c("McFadden","CoxSnell","Nagelkerke","Efron"))

####### PPSEUDO R-SQUARED INTERPRETATION #######

Based on the Pseudo R-squared values produced by the different models, it suggests that the 
Inverse Model would be the better model in terms of the explainability of the response variable "stay"




#=================================== OVERALL SUMMARY =====================================#
  
  The MLR model performs better based on the MSE, RMSE, and MAE,and MAPE indicating it is more 
  accurate in terms of prediction error. The inverse model performs better in terms of pseudo 
  R² values, suggesting it might explain more the variance in the data.





 













