Welcome to the Trading domain
-----------------------------

### Offers

Here, we start with an `Offer`. An `Offer` is provided by a `seller`, who is represented here by nothing
more than a `SellerId`.

When an offer is `placed`, it is in reference of a `Breed`. An ElePHPant only consists of a `Breed`. 
Since we do not want to tie the Trading and Herding contexts together, it is not required for
an ElePHPant on offer to come from a herd. In fact, in this context, herds don't matter.

Because we already know which Breeds are `desired` by the Shepherds in our system (this is known in the Herding context),
a `seller` can offer an ElePHPant directly to a Shepherd, resulting in an immediate `TradeForElePHPant` proposal.

After offering an ElePHPant for trade, the seller can add information to the offer. The most important thing 
here is that we do not have any contact information for the seller. This information falls under GDPR 
regulations and as such, we ask sellers to identify themselves when they put up offers.

Information identifying sellers is called `ContactInformation`. There can be multiple sets of ContactInformation
associated with an offer. In addition, a seller can add `Quality` to an offer. Quality comes in a 5-point scale. 
Finally, a seller can add one `Picture` to an offer. This is assumed to be a picture of the actual ElePHPant on offer.

Once there is an `Offer` on the table, others can propose a `Trade`. We allow two kinds of trade at this point, one
for `Money` and one for another `ElePHPant`. When an offer is placed, the seller should indicate what type of trade
is acceptable. This information is called `TradeLimits`, and can be updated at any time.

Finally, an `Offer` can be `withdrawn` at any time, until acceptance of a trade.

So when dealing with offers, we currently have the following **command** (event):

- PlaceOffer (OfferWasPlaced)
- PlaceOfferToShepherd (OfferWasPlacedToShepherd)
- AddPicture (PictureWasAdded)
- RemovePicture (PictureWasRemoved)
- AddContactInformation (ContactInformationWasAdded)
- RemoveContactInformation (ContactInformationWasRemoved)
- SetTradeLimits (TradeLimitsSet)
- UpdateQuality (QualityWasUpdated)
- WithdrawOffer (OfferWasWithdrawn)

### Trades

A Trade can be proposed by a `Shepherd`. Essentially, A trade for Money is anything that happens _outside_ of the 
system (at least for now). A trade for an ElePHPant can mean that the Shepherd selected a Breed to trade. The Herd 
is still irrelevant in the domain, and referenced as read-only data in the front-end to make a choice.  

A trade is `proposed` against an `Offer`.
A trade can be `retracted` by the Shepherd at any time.
A trade can be `rejected` by the seller. This can contain a reason why.
A trade can be `accepted` by the seller. Once this happens, the shepherd proposing trade becomes the `Buyer` and the trade is complete.

- ProposeTradeForMoney (TradeForMoneyWasProposed)
- ProposeTradeForElePHPant (TradeForElePHPantWasProposed)
- RetractTradeProposition (TradePropositionRetracted) 
- RejectTrade (TradeRejected)
- AcceptTrade (TradeAccepted)

### Effects on Herds

Since trading (potentially) has an impact on a Shepherd's Herd, the herding domain will respond to trade event `AcceptTrade`.
When a trade is accepted, there is a `Seller` and a `Buyer` and a `Breed`.

The seller needs to make the decision if that `Breed` comes from their own `Herd`:
- if to, this results in a `TransferElePHPant` command to move an ElePHPant from the seller's Herd to the buyer's Herd
- if not, the result is a regular `AdoptElePHPant` command to add the `Breed` to the buyer's herd.

In addition to the above:
If the trade was a `ProposeTradeForElePHPant` then the buyer needs to make the decision if that `Breed` comes from their own `Herd`:
- if to, this results in a `TransferElePHPant` command to move an ElePHPant from the buyers's Herd to the sellers's Herd


Decisions
---------

**2018-12-02** In order to separate Trading and Herding completely, we will not require an ElePHPantId in the Trading
context. 
- @ramondelafuente
- @jaspernbrouwer

**2018-10-19** The GDPR regulations require us to ask explicit consent before storing and using personal information.
We will try to keep that information to a minimum _and_ not use that information for anything else.
In addition, we will need to put a process in place for users to request their data - and have a way for that data to 
be removed.
Further decisions will follow regarding: not storing contact information inside events, or storing only 
encypted contact information in events. or something else entirely.
- @ramondelafuente

**2018-10-19** We will start off with a single image for an offer. A new image can replace the existing one, and the
existing one can be removed.
- @ramondelafuente
