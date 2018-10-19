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

Because trading can affect a herd, there is an events from the `Trading` context that we wish to respond to.

- Trading/TradeFinalized

After a trade is completed, if the `Seller` has the sold `Breed` in their `Herd`, we wil ask them to identify
the sold ElePHPant - and move a specific `ElePHPantId` to the `Buyer`'s herd.
After a trade is completed, if the `Seller` does not have the sold `Breed` in their `Herd`, we wil generate
a new `ElePHPantId` for the sold `Breed` to the `Buyer`'s herd.
After a trade is completed, if the `Buyer` traded a `Breed` in their `Herd`, we wil ask them to identify
the traded ElePHPant - and move a specific `ElePHPantId` to the `Seller`'s herd.
After a trade is completed, if the `Buyer` does not have traded `Breed` in their `Herd`, we wil generate
a new `ElePHPantId` for the traded `Breed` to the `Seller`'s herd.

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
