<?php

namespace esc\Classes;


use Maniaplanet\DedicatedServer\Connection;

/**
 * Class Server
 *
 * XML-RPC helper class. See {@see https://doc.maniaplanet.com/dedicated-server/references/xml-rpc-methods} for a full list of all methods and their arguments.
 *
 * @package esc\Classes
 *
 * @method static bool authenticate(string $string, string $string)
 * @method static bool changeAuthPassword(string $string, string $string)
 * @method static bool enableCallbacks(bool $boolean)
 * @method static bool setApiVersion(string $string)
 * @method static object getVersion()
 * @method static object getStatus()
 * @method static bool quitGame()
 * @method static bool callVote(string $string)
 * @method static bool callVoteEx(string $string, double $double, int $int, int $int)
 * @method static bool internalCallVote()
 * @method static bool cancelVote()
 * @method static object getCurrentCallVote()
 * @method static bool setCallVoteTimeOut(int $int)
 * @method static object getCallVoteTimeOut()
 * @method static bool setCallVoteRatio(double $double)
 * @method static double getCallVoteRatio()
 * @method static bool setCallVoteRatios(array $array)
 * @method static array getCallVoteRatios()
 * @method static bool setCallVoteRatiosEx(bool $boolean, array $array)
 * @method static array getCallVoteRatiosEx()
 * @method static bool chatSendServerMessage(string $message, string $login = null, bool $multicall = false)
 * @method static bool chatSend(string $string)
 * @method static bool chatSendToLanguage(array $array, string $string)
 * @method static bool chatSendToLogin(string $string, string $string)
 * @method static bool chatSendToId(string $string, int $int)
 * @method static array getChatLines()
 * @method static bool chatEnableManualRouting(bool $boolean, bool $boolean)
 * @method static bool chatForwardToLogin(string $string, string $string, string $string)
 * @method static bool sendNotice(string $string, string $string, int $int)
 * @method static bool sendNoticeToId(int $int, string $string, int $int, int $int)
 * @method static bool sendNoticeToLogin(string $string, string $string, string $string, int $int)
 * @method static bool sendDisplayManialinkPage($recipient, $manialinks, $timeout = 0, $hideOnClick = false, $multicall = false)
 * @method static bool sendDisplayManialinkPageToId(int $int, string $string, int $int, bool $boolean)
 * @method static bool sendDisplayManialinkPageToLogin(string $string, string $string, int $int, bool $boolean)
 * @method static bool sendHideManialinkPage()
 * @method static bool sendHideManialinkPageToId(int $int)
 * @method static bool sendHideManialinkPageToLogin(string $string)
 * @method static array getManialinkPageAnswers()
 * @method static bool sendOpenLinkToId(int $int, string $string, int $int)
 * @method static bool sendOpenLinkToLogin(string $string, string $string, int $int)
 * @method static bool kick(string $string, string $string)
 * @method static bool kickId(int $int, string $string)
 * @method static bool ban(string $string, string $string)
 * @method static bool banAndBlackList(string $string, string $string, bool $boolean)
 * @method static bool banId(int $int, string $string)
 * @method static bool unBan(string $string)
 * @method static bool cleanBanList()
 * @method static array getBanList(int $int, int $int)
 * @method static bool blackList(string $string)
 * @method static bool blackListId(int $int)
 * @method static bool unBlackList(string $string)
 * @method static bool cleanBlackList()
 * @method static array getBlackList(int $int, int $int)
 * @method static bool loadBlackList(string $string)
 * @method static bool saveBlackList(string $string)
 * @method static bool addGuest(string $string)
 * @method static bool addGuestId(int $int)
 * @method static bool removeGuest(string $string)
 * @method static bool removeGuestId(int $int)
 * @method static bool cleanGuestList()
 * @method static array getGuestList(int $int, int $int)
 * @method static bool loadGuestList(string $string)
 * @method static bool saveGuestList(string $string)
 * @method static bool setBuddyNotification(string $string, bool $boolean)
 * @method static bool getBuddyNotification(string $string)
 * @method static bool writeFile(string $string, string $base64)
 * @method static bool tunnelSendDataToId(int $int, string $base64)
 * @method static bool tunnelSendDataToLogin(string $string, string $base64)
 * @method static bool echo (string $string, string $string)
 * @method static bool ignore(string $string)
 * @method static bool ignoreId(int $int)
 * @method static bool unIgnore(string $string)
 * @method static bool unIgnoreId(int $int)
 * @method static bool cleanIgnoreList()
 * @method static array getIgnoreList(int $int = null, int $int = null)
 * @method static int pay(string $string, int $int, string $string)
 * @method static int sendBill(string $string, int $int, string $string, string $string = null)
 * @method static object getBillState(int $int)
 * @method static int getServerPlanets()
 * @method static object getSystemInfo()
 * @method static bool setConnectionRates(int $int, int $int)
 * @method static array getServerTags()
 * @method static bool setServerTag(string $string, string $string)
 * @method static bool unsetServerTag(string $string)
 * @method static bool resetServerTags()
 * @method static bool setServerName(string $string)
 * @method static string getServerName()
 * @method static bool setServerComment(string $string)
 * @method static string getServerComment()
 * @method static bool setHideServer(int $int)
 * @method static int getHideServer()
 * @method static bool isRelayServer()
 * @method static bool setServerPassword(string $string)
 * @method static string getServerPassword()
 * @method static bool setServerPasswordForSpectator(string $string)
 * @method static string getServerPasswordForSpectator()
 * @method static bool setMaxPlayers(int $int)
 * @method static object getMaxPlayers()
 * @method static bool setMaxSpectators(int $int)
 * @method static object getMaxSpectators()
 * @method static bool setLobbyInfo(bool $boolean, int $int, int $int, double $double)
 * @method static object getLobbyInfo()
 * @method static bool customizeQuitDialog(string $string, string $string, bool $boolean, int $int)
 * @method static bool keepPlayerSlots(bool $boolean)
 * @method static bool isKeepingPlayerSlots()
 * @method static bool enableP2PUpload(bool $boolean)
 * @method static bool isP2PUpload()
 * @method static bool enableP2PDownload(bool $boolean)
 * @method static bool isP2PDownload()
 * @method static bool allowMapDownload(bool $boolean)
 * @method static bool isMapDownloadAllowed()
 * @method static string gameDataDirectory()
 * @method static string getMapsDirectory()
 * @method static string getSkinsDirectory()
 * @method static bool setTeamInfo(string $string, double $double, string $string, string $string, double $double, string $string, string $string, double $double, string $string)
 * @method static object getTeamInfo(int $int)
 * @method static bool setForcedClubLinks(string $string, string $string)
 * @method static object getForcedClubLinks()
 * @method static string connectFakePlayer()
 * @method static bool disconnectFakePlayer(string $string)
 * @method static object getDemoTokenInfosForPlayer(string $string)
 * @method static bool disableHorns(bool $boolean)
 * @method static bool areHornsDisabled()
 * @method static bool disableServiceAnnounces(bool $boolean)
 * @method static bool areServiceAnnouncesDisabled()
 * @method static bool autoSaveReplays(bool $boolean)
 * @method static bool autoSaveValidationReplays(bool $boolean)
 * @method static bool isAutoSaveReplaysEnabled()
 * @method static bool isAutoSaveValidationReplaysEnabled()
 * @method static bool saveCurrentReplay(string $string)
 * @method static bool saveBestGhostsReplay(string $string, string $string)
 * @method static string getValidationReplay(string $string)
 * @method static bool setLadderMode(int $int)
 * @method static object getLadderMode()
 * @method static object getLadderServerLimits()
 * @method static bool setVehicleNetQuality(int $int)
 * @method static object getVehicleNetQuality()
 * @method static bool setServerOptions(object $struct)
 * @method static object getServerOptions()
 * @method static bool setForcedTeams(bool $boolean)
 * @method static bool getForcedTeams()
 * @method static bool setForcedMods(bool $boolean, array $array)
 * @method static object getForcedMods()
 * @method static bool setForcedMusic(bool $boolean, string $string)
 * @method static object getForcedMusic()
 * @method static bool setForcedSkins(array $array)
 * @method static array getForcedSkins()
 * @method static string getLastConnectionErrorMessage()
 * @method static bool setRefereePassword(string $string)
 * @method static string getRefereePassword()
 * @method static bool setRefereeMode(int $int)
 * @method static int getRefereeMode()
 * @method static bool setUseChangingValidationSeed(bool $boolean)
 * @method static object getUseChangingValidationSeed()
 * @method static bool setClientInputsMaxLatency(int $int)
 * @method static int getClientInputsMaxLatency()
 * @method static bool setWarmUp(bool $boolean)
 * @method static bool getWarmUp()
 * @method static string getModeScriptText()
 * @method static bool setModeScriptText(string $string)
 * @method static object getModeScriptInfo()
 * @method static object getModeScriptSettings()
 * @method static bool setModeScriptSettings(object $struct)
 * @method static bool sendModeScriptCommands(object $struct)
 * @method static bool setModeScriptSettingsAndCommands(object $struct, object $struct)
 * @method static object getModeScriptVariables()
 * @method static bool setModeScriptVariables(object $struct)
 * @method static bool triggerModeScriptEvent(string $string, string $string)
 * @method static bool triggerModeScriptEventArray(string $string, array $array)
 * @method static object getScriptCloudVariables(string $string, string $string)
 * @method static bool setScriptCloudVariables(string $string, string $string, object $struct)
 * @method static bool restartMap()
 * @method static bool nextMap()
 * @method static bool autoTeamBalance()
 * @method static bool stopServer()
 * @method static bool forceEndRound()
 * @method static bool setGameInfos(object $struct)
 * @method static object getCurrentGameInfo()
 * @method static object getNextGameInfo()
 * @method static object getGameInfos()
 * @method static bool setGameMode(int $int)
 * @method static int getGameMode()
 * @method static bool setChatTime(int $int)
 * @method static object getChatTime()
 * @method static bool setFinishTimeout(int $int)
 * @method static object getFinishTimeout()
 * @method static bool setAllWarmUpDuration(int $int)
 * @method static object getAllWarmUpDuration()
 * @method static bool setDisableRespawn(bool $boolean)
 * @method static object getDisableRespawn()
 * @method static bool setForceShowAllOpponents(int $int)
 * @method static object getForceShowAllOpponents()
 * @method static bool setScriptName(string $string)
 * @method static object getScriptName()
 * @method static bool setTimeAttackLimit(int $int)
 * @method static object getTimeAttackLimit()
 * @method static bool setTimeAttackSynchStartPeriod(int $int)
 * @method static object getTimeAttackSynchStartPeriod()
 * @method static bool setLapsTimeLimit(int $int)
 * @method static object getLapsTimeLimit()
 * @method static bool setNbLaps(int $int)
 * @method static object getNbLaps()
 * @method static bool setRoundForcedLaps(int $int)
 * @method static object getRoundForcedLaps()
 * @method static bool setRoundPointsLimit(int $int)
 * @method static object getRoundPointsLimit()
 * @method static bool setRoundCustomPoints(array $array, bool $boolean)
 * @method static array getRoundCustomPoints()
 * @method static bool setUseNewRulesRound(bool $boolean)
 * @method static object getUseNewRulesRound()
 * @method static bool setTeamPointsLimit(int $int)
 * @method static object getTeamPointsLimit()
 * @method static bool setMaxPointsTeam(int $int)
 * @method static object getMaxPointsTeam()
 * @method static bool setUseNewRulesTeam(bool $boolean)
 * @method static object getUseNewRulesTeam()
 * @method static bool setCupPointsLimit(int $int)
 * @method static object getCupPointsLimit()
 * @method static bool setCupRoundsPerMap(int $int)
 * @method static object getCupRoundsPerMap()
 * @method static bool setCupWarmUpDuration(int $int)
 * @method static object getCupWarmUpDuration()
 * @method static bool setCupNbWinners(int $int)
 * @method static object getCupNbWinners()
 * @method static int getCurrentMapIndex()
 * @method static int getNextMapIndex()
 * @method static bool setNextMapIndex(int $int)
 * @method static bool setNextMapIdent(string $string)
 * @method static bool jumpToMapIndex(int $int)
 * @method static bool jumpToMapIdent(string $string)
 * @method static object getCurrentMapInfo()
 * @method static object getNextMapInfo()
 * @method static object getMapInfo(string $string)
 * @method static bool checkMapForCurrentServerParams(string $string)
 * @method static array getMapList(int $int = 0, int $int = 0)
 * @method static bool addMap(string $string)
 * @method static int addMapList(array $array)
 * @method static bool removeMap(string $string)
 * @method static int removeMapList(array $array)
 * @method static bool insertMap(string $filename)
 * @method static int insertMapList(array $array)
 * @method static bool chooseNextMap(string $filename)
 * @method static int chooseNextMapList(array $array)
 * @method static int loadMatchSettings(string $string)
 * @method static int appendPlaylistFromMatchSettings(string $string)
 * @method static int saveMatchSettings(string $string)
 * @method static int insertPlaylistFromMatchSettings(string $string)
 * @method static array getPlayerList(int $int = 0, int $int = 0, int $int = 0)
 * @method static \Maniaplanet\DedicatedServer\Structures\PlayerInfo getPlayerInfo(string $string, int $int = 0)
 * @method static \Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo getDetailedPlayerInfo(string $string)
 * @method static object getMainServerPlayerInfo(int $int)
 * @method static array getCurrentRanking(int $int, int $int)
 * @method static array getCurrentRankingForLogin(string $string)
 * @method static int getCurrentWinnerTeam()
 * @method static bool forceScores(array $array, bool $boolean)
 * @method static bool forcePlayerTeam(string $string, int $int)
 * @method static bool forcePlayerTeamId(int $int, int $int)
 * @method static bool forceSpectator(string $string, int $int)
 * @method static bool forceSpectatorId(int $int, int $int)
 * @method static bool forceSpectatorTarget(string $string, string $string, int $int)
 * @method static bool forceSpectatorTargetId(int $int, int $int, int $int)
 * @method static bool spectatorReleasePlayerSlot(string $string)
 * @method static bool spectatorReleasePlayerSlotId(int $int)
 * @method static bool manualFlowControlEnable(bool $boolean)
 * @method static bool manualFlowControlProceed()
 * @method static int manualFlowControlIsEnabled()
 * @method static string manualFlowControlGetCurTransition()
 * @method static string checkEndMatchCondition()
 * @method static object getNetworkStats()
 * @method static bool startServerLan()
 * @method static bool startServerInternet()
 * @method static void executeMulticall()
 * @method static array executeCallbacks()
 */
class Server
{
    private static $rpc;

    /**
     * Initialize the connection to the maniaplanet-dedicated-server.
     *
     * @param $host
     * @param $port
     * @param $timeout
     * @param $login
     * @param $password
     *
     * @throws \Maniaplanet\DedicatedServer\InvalidArgumentException
     */
    public static function init($host, $port, $timeout, $login, $password)
    {
        self::$rpc = Connection::factory($host, $port, $timeout, $login, $password);
        self::$rpc->enableCallbacks();

        //Hide all previously send manialinks
        // self::call('SendHideManialinkPage');
    }

    /**
     * Get the rpc-connection-instance of the maniaplanet-package (Useful to get type-hints).
     *
     * @return \Maniaplanet\DedicatedServer\Connection
     */
    public static function rpc(): Connection
    {
        return self::$rpc;
    }

    public static function isFilenameInSelection(string $filename): bool
    {
        return collect(Server::getMapList())->contains('fileName', $filename);
    }

    /**
     * Call an rpc-method.
     *
     * @param string $rpc_func
     * @param null   $args
     */
    public static function call(string $rpc_func, $args = null)
    {
        self::rpc()->execute($rpc_func, $args);
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::$rpc, $name)) {
            return call_user_func_array([self::$rpc, $name], $arguments);
        } else {
            Log::error("Calling undefined rpc-method: $name");
        }

        return null;
    }
}