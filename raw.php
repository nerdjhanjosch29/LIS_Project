<?php

USE [LIS_db]
GO
/****** Object:  StoredProcedure [dbo].[Binloadings]    Script Date: 5/8/2024 8:56:06 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE [dbo].[Binloadings]
@BinloadingID int,
@ControlNo nchar(10),
@PlantID int,
@CheckerID int,
@WarehouseID int,
@WarehousePartitionID float,
@WarehousePartitionStockID int,
@BinloadDate date,
@BinloadDateTime datetime,
@RawMaterialID int,
@Quantity int,
@UserID int
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @result int = 0;
	DECLARE @BeginQty int = 0;
	DECLARE @BeginWeight int = 0;
	DECLARE @BeginPrice float = 0;
	DECLARE @BeginQtys int = 0;
	DECLARE @BeginWeights int = 0;
	DECLARE @BeginPrices float = 0;
	DECLARE @EndingQty int = 0;
	DECLARE @EndingWeight int = 0;
	DECLARE @EndingPrice float = 0;
	DECLARE @IncomingQtys int = 0;
	DECLARE @IncomingWeights int =0;
	DECLARE @IncomingPrices float = 0;
	DECLARE @BinloadingQtys int = 0;
	DECLARE @BinloadingWeights int = 0;
	DECLARE @BinloadingPrices int = 0;
	DECLARE @CondemQtys int = 0;
	DECLARE @CondemWeights int = 0;
	DECLARE @CondemPrices int = 0;
	DECLARE @TotalIncomingQty int = 0;
	DECLARE @TotalIncomingWeight int = 0;
	DECLARE @TotalIncomingPrice int = 0;
	DECLARE @TotalCondemQty int = 0;
	DECLARE @TotalCondemWeight int = 0;
	DECLARE @TotalCondemPrice int = 0;
	DECLARE @TotalEndingWeight int = 0;
	DECLARE @TotalEndingPrice int = 0;
	DECLARE @TotalBinloadingQty int =0;
	DECLARE @TotalBinloadingWeight int = 0;
	DECLARE @TotalBinloadingPrice int = 0;
	DECLARE @EndingQtys int = 0;
	DECLARE @EndingWeights int = 0;
	DECLARE @EndingPrices int = 0;
	DECLARE @LimitQty int = 0;
	DECLARE @LimitWeight int = 0;
	DECLARE @LimitPrice int = 0;
	DECLARE @StartDate Date = '2024-01-01';
	DECLARE @DateEnding Date = GETDATE();
	DECLARE @RawMatQuantity int = 0;
	DECLARE @RawMatWeight int = 0;
	DECLARE @TotalQty int =0;
	DECLARE @TotalWeight int =0;
	DECLARE @TotalPrice int = 0;
	DECLARE @EndingsQty int =0;
	DECLARE @EndingsWeight int =0;
	DECLARE @EndingsPrice int = 0;
	
	IF @RawMaterialInventoryID = 0
	BEGIN

	SET @IncomingQtys =(SELECT IncomingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @IncomingWeights =(SELECT IncomingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @IncomingPrices =(SELECT IncomingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @BinloadingQtys = (SELECT BinloadingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @BinloadingWeights = (SELECT BinloadingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @BinloadingPrices = (SELECT BinloadingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @CondemQtys = (SELECT CondemQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @CondemWeights = (SELECT CondemWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @CondemPrices = (SELECT CondemPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);

	SET @BeginQty =(SELECT BeginQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
	SET @BeginWeight =(SELECT BeginWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @BeginPrice =(SELECT BeginPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);

	SET @EndingsQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
	SET @EndingsWeight =(SELECT EndingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @EndingsPrice =(SELECT EndingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);

	SET @TotalBinloadingQty = @BinloadingQty + @BinloadingQtys;
	SET @TotalBinloadingWeight = @BinloadingWeights + @BinloadingWeight;
	SET @TotalBinloadingPrice = @BinloadingPrice + @BinloadingPrices;

	SET @TotalCondemQty = @CondemQtys + @CondemQty;
	SET @TotalCondemWeight = @CondemWeights + @CondemWeight; 
	SET @TotalCondemPrice = @CondemPrices + @CondemPrice;

	SET @EndingQty = @BeginQty + @IncomingQtys - @TotalBinloadingQty - @TotalCondemQty;
	SET @EndingWeight = @BeginWeight + @TotalIncomingWeight - @TotalCondemWeight -@TotalBinloadingWeight;
	SET @EndingPrice = @BeginPrice + @TotalIncomingPrice - @TotalBinloadingPrice - @TotalCondemPrice;

	SET @LimitQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
	SET @LimitWeight =(SELECT EndingWeight FROM RawMaterialInventory   WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @LimitPrice =(SELECT EndingPrice FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);


			    IF @TotalBinloadingQty > @EndingsQty OR @TotalBinloadingWeight > @EndingsWeight OR @TotalBinloadingPrice > @EndingsPrice OR @TotalCondemPrice >@EndingsPrice
				BEGIN
				SET @result =-1
				END
				ELSE
				BEGIN
				IF @BinloadDate = @StartDate
				BEGIN
				UPDATE RawMaterialInventory SET RawMaterialID =@RawMaterialID, InventoryDate = @BinloadDate, BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,
				 BinloadingQty = @TotalBinloadingQty,BinloadingWeight = @TotalBinloadingWeight, BinloadingPrice = @TotalBinloadingPrice,
				CondemQty = @TotalCondemQty, CondemWeight = @TotalCondemWeight, CondemPrice = @TotalCondemPrice, EndingQty = @EndingQty, EndingWeight = @EndingWeight, EndingPrice = @EndingPrice WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
				SET @result = 1;
										WHILE @BinloadDate <= GETDATE()
										BEGIN  
										SET @IncomingQtys =(SELECT IncomingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @IncomingWeights =(SELECT IncomingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @IncomingPrices =(SELECT IncomingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @CondemQtys = (SELECT CondemQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @CondemWeights = (SELECT CondemWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @CondemPrices = (SELECT CondemPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @BinloadingQtys = (SELECT BinloadingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @BinloadingWeights = (SELECT BinloadingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
										SET @BinloadingPrices = (SELECT BinloadingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											IF @IncomingQtys <> 0 
											BEGIN
											SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
											SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
											SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;
												UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight,BeginPrice = @BeginPrice, EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
												WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
											END
											ELSE
											BEGIN
											SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
											SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
											SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;

												UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
												WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
											END
												/* SELECT last EndingQty */
									 				SET @BeginQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
													SET @BeginWeight =(SELECT EndingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @BeginPrice =(SELECT EndingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												 SET @BinloadDate = DATEADD(Day,1,@BinloadDate);
										/*END for WHILE*/
										END

									-- SAVE Last EndingQty in Quantity of RawMaterial
					IF @result = 2 OR @result = 1
					BEGIN
					SELECT @RawMatQuantity = EndingQty FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
					UPDATE RawMaterial SET Quantity = @RawMatQuantity WHERE RawMaterialID = @RawMaterialID;
					END
					IF @result = 2 OR @result = 1
					BEGIN
					SELECT @RawMatWeight = EndingWeight FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
					UPDATE RawMaterial SET Weight = @RawMatWeight WHERE RawMaterialID = @RawMaterialID;
					END
								
					END

					--IF @BinloadDate = @StartDate
					ELSE
					BEGIN
					UPDATE RawMaterialInventory SET RawMaterialID =@RawMaterialID, InventoryDate = @BinloadDate, BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,
					 BinloadingQty = @TotalBinloadingQty,BinloadingWeight = @TotalBinloadingWeight, BinloadingPrice = @TotalBinloadingPrice,
					CondemQty = @TotalCondemQty, CondemWeight = @TotalCondemWeight, CondemPrice = @TotalCondemPrice, EndingQty = @EndingQty, EndingWeight = @EndingWeight, EndingPrice = @EndingPrice WHERE RawMaterialID = @RawMaterialID AND InventoryDate =@BinloadDate;
					SET @result = 1;
					-- SAVE Last EndingQty in Quantity of RawMaterial
											WHILE @BinloadDate <= GETDATE()
											BEGIN  
											SET @IncomingQtys =(SELECT IncomingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @IncomingWeights =(SELECT IncomingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @IncomingPrices =(SELECT IncomingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @CondemQtys = (SELECT CondemQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @CondemWeights = (SELECT CondemWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @CondemPrices = (SELECT CondemPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @BinloadingQtys = (SELECT BinloadingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @BinloadingWeights = (SELECT BinloadingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
											SET @BinloadingPrices = (SELECT BinloadingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);

												IF @IncomingQtys <> 0 
												BEGIN
												SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
												SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
												SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;
													UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight,BeginPrice = @BeginPrice, EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
													WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
												END
												ELSE
												BEGIN
												SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
												SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
												SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;

													UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
													WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
												END
													/* SELECT last EndingQty */
									 					SET @BeginQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
														SET @BeginWeight =(SELECT EndingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
														SET @BeginPrice =(SELECT EndingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													 SET @BinloadDate = DATEADD(Day,1,@BinloadDate);
											/*END for WHILE*/
											END
					IF @result = 2 OR @result = 1
					BEGIN
					SELECT @RawMatQuantity = EndingQty FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
					UPDATE RawMaterial SET Quantity = @RawMatQuantity WHERE RawMaterialID = @RawMaterialID;
					END
					IF @result = 2 OR @result = 1
					BEGIN
					SELECT @RawMatWeight = EndingWeight FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
					UPDATE RawMaterial SET Weight = @RawMatWeight WHERE RawMaterialID = @RawMaterialID;
					END

				  --END FOR ELSE OF IF @BinloadDate = @StartDate
					END
				END			
	--END FOR ELSE OF IF @RawMaterialID = 0
	END
	ELSE
	BEGIN 
	SET @BeginQty =(SELECT BeginQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
	SET @BeginWeight =(SELECT BeginWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @BeginPrice =(SELECT BeginPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @IncomingQtys =(SELECT IncomingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @IncomingWeights =(SELECT IncomingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @IncomingPrices =(SELECT IncomingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @LimitQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
	SET @LimitWeight =(SELECT EndingWeight FROM RawMaterialInventory   WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
	SET @LimitPrice =(SELECT EndingPrice FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
			    IF @BinloadingQty > @LimitQty OR @CondemQty > @LimitQty OR @BinloadingWeight > @LimitWeight OR @CondemWeight > @LimitWeight OR @BinloadingPrice > @LimitPrice OR @CondemPrice >@LimitPrice
				BEGIN
				SET @result =-1
				END
				ELSE
				BEGIN
				IF @BinloadDate = @StartDate
				BEGIN
				UPDATE RawMaterialInventory SET RawMaterialID =@RawMaterialID, InventoryDate = @BinloadDate, BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice, 
				BinloadingQty = @BinloadingQty,BinloadingWeight = @BinloadingWeight, BinloadingPrice = @BinloadingPrice,
				CondemQty = @CondemQty, CondemWeight = @CondemWeight, CondemPrice = @CondemPrice, EndingQty = @EndingQty, EndingWeight = @EndingWeight, EndingPrice = @EndingPrice WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
				SET @result = 2;
												WHILE @BinloadDate <= GETDATE()
												BEGIN  
												SET @IncomingQtys =(SELECT IncomingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @IncomingWeights =(SELECT IncomingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @IncomingPrices =(SELECT IncomingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @CondemQtys = (SELECT CondemQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @CondemWeights = (SELECT CondemWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @CondemPrices = (SELECT CondemPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @BinloadingQtys = (SELECT BinloadingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @BinloadingWeights = (SELECT BinloadingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
												SET @BinloadingPrices = (SELECT BinloadingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);

													IF @IncomingQtys <> 0 
													BEGIN
													SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
													SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
													SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;
														UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight,BeginPrice = @BeginPrice, EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
														WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
													END
													ELSE
													BEGIN
													SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
													SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
													SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;

														UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
														WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
													END
														/* SELECT last EndingQty */
									 						SET @BeginQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
															SET @BeginWeight =(SELECT EndingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
															SET @BeginPrice =(SELECT EndingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
														 SET @BinloadDate = DATEADD(Day,1,@BinloadDate);
												/*END for WHILE*/
												END
							IF @result = 2 OR @result = 1
							BEGIN
							SELECT @RawMatQuantity = EndingQty FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
							UPDATE RawMaterial SET Quantity = @RawMatQuantity WHERE RawMaterialID = @RawMaterialID;
							END
							IF @result = 2 OR @result = 1
							BEGIN
							SELECT @RawMatWeight = EndingWeight FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
							UPDATE RawMaterial SET Weight = @RawMatWeight WHERE RawMaterialID = @RawMaterialID;
							END

							END
							ELSE
							BEGIN
							UPDATE RawMaterialInventory SET RawMaterialID =@RawMaterialID, InventoryDate = @BinloadDate, BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,
							BinloadingQty = @BinloadingQty,BinloadingWeight = @BinloadingWeight, 
							BinloadingPrice = @BinloadingPrice,
							CondemQty = @CondemQty, CondemWeight = @CondemWeight, CondemPrice = @CondemPrice, EndingQty = @EndingQty, EndingWeight = @EndingWeight, EndingPrice = @EndingPrice 
							WHERE RawMaterialID = @RawMaterialID AND InventoryDate =@BinloadDate;
							SET @result = 2;

													WHILE @BinloadDate <= GETDATE()
													BEGIN  
													SET @IncomingQtys =(SELECT IncomingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @IncomingWeights =(SELECT IncomingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @IncomingPrices =(SELECT IncomingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @CondemQtys = (SELECT CondemQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @CondemWeights = (SELECT CondemWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @CondemPrices = (SELECT CondemPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @BinloadingQtys = (SELECT BinloadingQty FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @BinloadingWeights = (SELECT BinloadingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
													SET @BinloadingPrices = (SELECT BinloadingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);

														IF @IncomingQtys <> 0 
														BEGIN
														SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
														SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
														SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;
															UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight,BeginPrice = @BeginPrice, EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
															WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
														END
														ELSE
														BEGIN
														SET @EndingQtys = @BeginQty + @IncomingQtys - @BinloadingQtys - @CondemQtys;
														SET @EndingWeights = @BeginWeight + @IncomingWeights - @BinloadingWeights - @CondemWeights;
														SET @EndingPrices = @BeginPrice + @IncomingPrices - @BinloadingPrices - @CondemPrices;

															UPDATE RawMaterialInventory SET BeginQty = @BeginQty, BeginWeight = @BeginWeight, BeginPrice = @BeginPrice,EndingQty = @EndingQtys, EndingWeight= @EndingWeights, EndingPrice = @EndingPrices
															WHERE RawMaterialID = @RawMaterialID AND InventoryDate = @BinloadDate;
														END
															/* SELECT last EndingQty */
									 							SET @BeginQty =(SELECT EndingQty FROM RawMaterialInventory WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID );
																SET @BeginWeight =(SELECT EndingWeight FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
																SET @BeginPrice =(SELECT EndingPrice FROM RawMaterialInventory  WHERE InventoryDate = @BinloadDate AND RawMaterialID = @RawMaterialID);
															 SET @BinloadDate = DATEADD(Day,1,@BinloadDate);
													/*END for WHILE*/
													END
							IF @result = 2 OR @result = 1
							BEGIN
							SELECT @RawMatQuantity = EndingQty FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
							UPDATE RawMaterial SET Quantity = @RawMatQuantity WHERE RawMaterialID = @RawMaterialID;
							END
							IF @result = 2 OR @result = 1
							BEGIN
							SELECT @RawMatWeight = EndingWeight FROM RawMaterialInventory WHERE RawMaterialID = @RawMaterialID AND InventoryDate = DATEADD(DAY, -1, @DateEnding);
							UPDATE RawMaterial SET Weight = @RawMatWeight WHERE RawMaterialID = @RawMaterialID;
							END
						  --END FOR ELSE OF IF @BinloadDate = @StartDate
							END
				END
		
	END
	SELECT @result AS result;
	

END


?>