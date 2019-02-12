Welcome to the Herding domain
-----------------------------

### Herds

Here, we start with a `Herd`. A `Herd` is tended to by a shepherd, who is represented here by nothing
more than a `ShepherdId`.

When a new herd starts, it is `Formed`. A herd consists of `ElePHPants`. When a herd accepts a new ElePHPant, 
that is called `Adopting`. When a shepherd removes an ElePHPant from a herd, that is called `Abandoning`. 
If a shepherd decides to end a herd, that entire herd is `Abandoned`. A Herd can also be `Renamed`.

Every `ElePHPant` is a certain `Breed`. When looking at a Herd, we can look at all it's ElePHPants, but
also at every _unique_ Breed in that Herd, and the total number of ElePHPants of each Breed.

A `Herd` can desire new `Breeds`. When a `Herd` no longer desires a certain `Breed`, it can `Eliminate` that desire.


So when dealing with herds, we currently have the following command (event):

- FormHerd (HerdWasFormed)
- AdoptElePHPant (ElePHPantWasAdoptedByHerd)
- AbandonElePHPant (ElePHPantWasAbandonedByHerd)
- AbandonHerd (HerdWasAbandoned)
- RenameHerd (HerdWasRenamed)
- DesireBreed (BreedWasDesiredByHerd)
- EliminateDesireForBreed (BreedDesireWasEliminatedByHerd)

### Trades

Since trading (potentially) has an impact on a Shepherd's Herd, the herding domain will respond to trade event `AcceptTrade`.
When a trade is accepted, there is a `Seller` and a `Buyer` and a `Breed`.

The seller needs to make the decision if that `Breed` comes from their own `Herd`:
- if to, this results in a `TransferElePHPant` command to move an ElePHPant from the seller's Herd to the buyer's Herd
- if not, the result is a regular `AdoptElePHPant` command to add the `Breed` to the buyer's herd (with a newly generated `ElePHPantId`)

In addition to the above:
If the trade was a `ProposeTradeForElePHPant` then the buyer needs to make the decision if that `Breed` comes from their own `Herd`:
- if to, this results in a `TransferElePHPant` command to move an ElePHPant from the buyers's Herd to the sellers's Herd
- if not, the result is a regular `AdoptElePHPant` command to add the `Breed` to the sellers's herd (with a newly generated `ElePHPantId`)


Decisions
---------

**2017-08-01** The domain is not concerned with users or user management.  We only need to identify a ShepherdId in order
to link to _something_ signifying a specific user in the outside world. 
- @ramondelafuente
- @jaspernbrouwer
- @mjmeijerman
- @verschoof


**2017-08-01** Herd tending is different from trading. A separate `Trading` domain will be used when we start implementing 
trades. Therefore, herds do not need to know about pictures of ElePHPants being sold or specific production batch numbers 
or other trade related data.
- @ramondelafuente
- @jaspernbrouwer
